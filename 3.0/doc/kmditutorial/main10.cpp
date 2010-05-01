/*
 * Copyright (C) 2004 Andrea Bergia <andreabergia2@yahoo.it>
 */

#include <kapplication.h>
#include <klocale.h>
#include <kaboutdata.h>
#include <kcmdlineargs.h>
#include <kconfig.h>

#include "kmditest.h"

static const char description[] =
    I18N_NOOP("A KMDI test application");

static const char version[] = "0.1";

int main(int argc, char **argv)
{
    // Default start-up routine
    KAboutData about("kmditest", I18N_NOOP("KMDITest"), version, description,
                     KAboutData::License_GPL, "(C) 2004 Andrea Bergia", 0, 0, "andreabergia2@yahoo.it");
    about.addAuthor( "Andrea Bergia", 0, "andreabergia2@yahoo.it" );
    KCmdLineArgs::init(argc, argv, &about);
    KApplication app;
    
    // Read MDI mode
    KConfig *c = app.config();
    QString strMode = c->readEntry( "MDIMode", "ideal" );
    KMdi::MdiMode mode = KMdi::IDEAlMode;
    if ( strMode == "toplevel" ) {
        mode = KMdi::ToplevelMode;
    } else if ( strMode == "childframe" ) {
        mode = KMdi::ChildframeMode;
    } else if ( strMode == "tabpage" ) {
        mode = KMdi::TabPageMode;
    }
    
    // Create main window
    KMDITest *mainWin = new KMDITest( mode );
    app.setMainWidget( mainWin );
    mainWin->show();

    // mainWin has WDestructiveClose flag by default, so it will delete itself.
    return app.exec();
}

