/*
 * Copyright (C) 2004 Andrea Bergia <andreabergia2@yahoo.it>
 */

#include "kmditest.h"

#include <kapplication.h>
#include <klocale.h>

#include <qlabel.h>
#include <kmdichildview.h>
#include <qlayout.h>
#include <kiconloader.h>
#include <kstdaction.h>

KMDITest::KMDITest()
    : KMdiMainFrm( 0, "KMDITest", KMdi::IDEAlMode ), m_childs( 0 )
{
    setXMLFile("kmditestui.rc");

    KStdAction::openNew( this, SLOT( openNewWindow() ), actionCollection() );
    KStdAction::quit( kapp, SLOT( quit() ), actionCollection() );

    createGUI( 0 );
}

KMDITest::~KMDITest()
{
}

void KMDITest::openNewWindow()
{
    // Add a child view
    m_childs ++;
    KMdiChildView *view = new KMdiChildView( i18n( "View %1" ).arg( m_childs ), this );
    ( new QHBoxLayout( view ) )->setAutoAdd( true );
    new QLabel( i18n( "Test of KMDI: view %1" ).arg( m_childs ), view );
    addWindow( view );
}

#include "kmditest.moc"
