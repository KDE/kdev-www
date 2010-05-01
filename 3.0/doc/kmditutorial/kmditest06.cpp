/*
 * Copyright (C) 2004 Andrea Bergia <andreabergia2@yahoo.it>
 */

#include "kmditest.h"

#include <kapplication.h>
#include <klocale.h>

#include <qlabel.h>
#include <qlayout.h>
#include <kiconloader.h>
#include <kstdaction.h>
#include <kstatusbar.h>
#include <kmdichildview.h> 

KMDITest::KMDITest()
    : KMdiMainFrm( 0, "KMDITest", KMdi::IDEAlMode ), m_childs( 0 )
{
    setXMLFile("kmditestui.rc");

    // Create some actions
    KStdAction::openNew( this, SLOT( openNewWindow() ), actionCollection() );
    KStdAction::close( this, SLOT( closeCurrent() ), actionCollection() );
    KStdAction::quit( kapp, SLOT( quit() ), actionCollection() );
    createGUI( 0 );
    
    // When we change view, change the status bar text
    connect( this, SIGNAL( viewActivated( KMdiChildView * ) ), 
        this, SLOT( currentChanged( KMdiChildView * ) ) );
        
    // Create the status bar
    statusBar()->message( i18n( "No view!" ) );
}

KMDITest::~KMDITest()
{
}

void KMDITest::openNewWindow()
{
    // Add a child view
    m_childs ++;
    KMdiChildView *view = new KMdiChildView( 
        i18n( "View %1" ).arg( m_childs ), this );
    ( new QHBoxLayout( view ) )->setAutoAdd( true );
    new QLabel( i18n( "Test of KMDI: view %1" ).arg( m_childs ), view );
    addWindow( view );
    currentChanged( view );
}

void KMDITest::currentChanged( KMdiChildView *current )
{
    // We can access the current window via current, or via the protected
    // member we inerithed m_pCurrentWindow
    
    statusBar()->message( i18n( "%1 activated" ).arg( current->tabCaption() ) );
}

void KMDITest::closeCurrent() 
{
    // If there's a current view, close it
    if ( m_pCurrentWindow != 0 ) {
        // Notify the removeal of the window into the status bar
        statusBar()->message( 
            i18n( "%1 removed" ).arg( m_pCurrentWindow->tabCaption() ) );
    
        // We could also call removeWindowFromMdi, but it doesn't delete the
        // pointer. This way, we're sure that the view will get deleted.
        closeWindow( m_pCurrentWindow );        
    }
}

#include "kmditest.moc"
