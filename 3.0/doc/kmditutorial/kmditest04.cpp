/*
 * Copyright (C) 2004 Andrea Bergia <andreabergia2@yahoo.it>
 */

#include "kmditest.h"

#include <qlabel.h>
#include <kmdichildview.h>
#include <klocale.h>
#include <qlayout.h>
#include <kiconloader.h>

KMDITest::KMDITest()
    : KMdiMainFrm( 0, "KMDITest", KMdi::IDEAlMode )
{
    setXMLFile("kmditestui.rc");

    // Add a sample child view
    KMdiChildView *view1 = new KMdiChildView( i18n( "View 1" ), this );
    ( new QHBoxLayout( view1 ) )->setAutoAdd( true );
    ( new QLabel( view1 ) )->setPixmap( BarIcon( "konsole" ) );
    new QLabel( i18n( "Test of KMDI: view 1" ), view1 );
    addWindow( view1 );

    // Add another sample child view
    KMdiChildView *view2 = new KMdiChildView( i18n( "View 2" ), this );
    ( new QHBoxLayout( view2 ) )->setAutoAdd( true );
    ( new QLabel( view2 ) )->setPixmap( BarIcon( "quanta" ) );
    new QLabel( i18n( "Test of KMDI: view 2" ), view2 );
    addWindow( view2 );
}

KMDITest::~KMDITest()
{
}

#include "kmditest.moc"
