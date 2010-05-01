/*
 * Copyright (C) 2004 Andrea Bergia <andreabergia2@yahoo.it>
 */

#include "kmditest.h"

#include <cassert>
using namespace std;

#include <kapplication.h>
#include <klocale.h>
#include <kdebug.h>
#include <kconfig.h>

#include <qlabel.h>
#include <qlayout.h>
#include <kiconloader.h>
#include <kstdaction.h>
#include <kstatusbar.h>
#include <kmdichildview.h>
#include <klistbox.h>
#include <kactionclasses.h>

KMDITest::KMDITest( KMdi::MdiMode mode )
    : KMdiMainFrm( 0, "KMDITest", mode ), m_childs( 0 )
{
    setXMLFile("kmditestui.rc");

    // Create some actions
    KStdAction::openNew( this, SLOT( openNewWindow() ), actionCollection() );
    KStdAction::close( this, SLOT( closeCurrent() ), actionCollection() );
    KStdAction::quit( this, SLOT( close() ), actionCollection() );
    
    // Allow the user to change the MDI mode
    KToggleAction *tl = new KRadioAction( i18n( "Top level mode" ), KShortcut(),
        this, SLOT( switchToToplevelMode() ), actionCollection(), "toplevel" );
    tl->setExclusiveGroup( "MDIMode" );
    tl->setChecked( mode == KMdi::ToplevelMode );
    KToggleAction *cf = new KRadioAction( i18n( "Child frame mode" ), KShortcut(),
        this, SLOT( switchToChildframeMode() ), actionCollection(), "childframe" );
    cf->setExclusiveGroup( "MDIMode" );
    cf->setChecked( mode == KMdi::ChildframeMode );
    KToggleAction *tp = new KRadioAction( i18n( "Tab page mode" ), KShortcut(),
        this, SLOT( switchToTabPageMode() ), actionCollection(), "tabpage" );
    tp->setExclusiveGroup( "MDIMode" );
    tp->setChecked( mode == KMdi::TabPageMode );
    KToggleAction *id = new KRadioAction( i18n( "IDEAl mode" ), KShortcut(),
        this, SLOT( switchToIDEAlMode() ), actionCollection(), "ideal" );
    id->setExclusiveGroup( "MDIMode" );
    id->setChecked( mode == KMdi::IDEAlMode );
    
    createGUI( 0 );
    
    // When we change view, change the status bar text
    connect( this, SIGNAL( viewActivated( KMdiChildView * ) ), 
        this, SLOT( currentChanged( KMdiChildView * ) ) );
        
    // Create the status bar
    statusBar()->message( i18n( "No view!" ) );
    
    // Create the list of the opened windows
    m_listBox = new KListBox( this );
    m_listBox->setCaption( i18n( "Opened windows" ) );
    addToolWindow( m_listBox, KDockWidget::DockLeft, getMainDockWidget() );
    
    connect( m_listBox, SIGNAL( executed( QListBoxItem * ) ), this,
        SLOT( listBoxExecuted( QListBoxItem * ) ) );
}

KMDITest::~KMDITest()
{
}

void KMDITest::openNewWindow()
{
    // Add a child view
    m_childs ++;
    
    // The child view will be our child only if we aren't in Toplevel mode
    KMdiChildView *view = new KMdiChildView(
        i18n( "View %1" ).arg( m_childs ), 
        ( mdiMode() == KMdi::ToplevelMode ) ? 0 : this );
    ( new QHBoxLayout( view ) )->setAutoAdd( true );
    new QLabel( i18n( "Test of KMDI: view %1" ).arg( m_childs ), view );

    // Add the item to the window list
    m_window.append( view );
    m_listBox->insertItem( view->tabCaption() );

    // Add to the MDI and set as current
    if ( mdiMode() == KMdi::ToplevelMode ) {
        addWindow( view, KMdi::Detach );
    } else {
        addWindow( view );
    }
    currentChanged( view );
    
    // Handle the request of killing
    connect( view, SIGNAL( childWindowCloseRequest( KMdiChildView * ) ),
        this, SLOT( childClosed( KMdiChildView * ) ) );
}

void KMDITest::currentChanged( KMdiChildView *current )
{
    // Update status bar and list box
    statusBar()->message( i18n( "%1 activated" ).arg( current->tabCaption() ) );
    
    QListBoxItem *item = m_listBox->findItem( current->tabCaption() );
    assert( item );
    m_listBox->setCurrentItem( item );
}

void KMDITest::closeCurrent()
{
    // If there's a current view, close it
    if ( m_pCurrentWindow != 0 ) {
        // Notify the removeal of the window into the status bar
        statusBar()->message(
            i18n( "%1 removed" ).arg( m_pCurrentWindow->tabCaption() ) );

        // Remove from the window list
        m_window.remove( m_window.find( m_pCurrentWindow ) );
        
        // Remove from the list box
        QListBoxItem *item = m_listBox->findItem( m_pCurrentWindow->tabCaption() );
        assert( item );
        delete item;

        // We could also call removeWindowFromMdi, but it doesn't delete the
        // pointer. This way, we're sure that the view will get deleted.
        closeWindow( m_pCurrentWindow );
    }
    
    // Synchronize combo box
    if ( m_pCurrentWindow ) {
        currentChanged( m_pCurrentWindow );
    }
}

void KMDITest::listBoxExecuted( QListBoxItem *item )
{
    // Get the current item's text
    QString text = item->text();

    // Active the corresponding tab
    for ( QValueList< KMdiChildView *>::iterator it = m_window.begin();
        it != m_window.end(); ++it ) {
        // Get the view
        KMdiChildView *view = *it;
        assert( view );

        // Is the view we need to show?
        if ( view->caption() == text ) {
            view->activate();
            break;
        }
    }
}

void KMDITest::childClosed( KMdiChildView * w )
{
    assert( w );

    // Set as active
    w->activate();
    assert( w == m_pCurrentWindow );

    // Notify the removeal of the window into the status bar
    statusBar()->message(
        i18n( "%1 removed" ).arg( w->tabCaption() ) );

    // Remove from the window list
    m_window.remove( m_window.find( w ) );
    
    // Remove from the list box
    QListBoxItem *item = m_listBox->findItem( w->tabCaption() );
    assert( item );
    delete item;

    // Remove the view from MDI, BUT NOT DELETE IT! It gets automatically
    // deleted by QT, since it was closed.
    removeWindowFromMdi( w );
}

bool KMDITest::queryClose()
{
    // Save current MDI mode
    KConfig *c = kapp->config();
    
    // Put in value a matching string value
    QString value;
    switch ( mdiMode() ) {
        case KMdi::ToplevelMode:
            value = "toplevel";
            break;
        
        case KMdi::ChildframeMode:
            value = "childframe";
            break;
        
        case KMdi::TabPageMode:
            value = "tabpage";
            break;
        
        case KMdi::IDEAlMode:
            value = "ideal";
            break;
            
        case KMdi::UndefinedMode:
            value = "ideal";
            break;
    }
    
    // Write it!
    c->writeEntry( "MDIMode", value );
    c->sync();

    // Allow to close the window
    return true;
}

#include "kmditest.moc"
