/*
 * Copyright (C) 2004 Andrea Bergia <andreabergia2@yahoo.it>
 */

#ifndef _KMDITEST_H_
#define _KMDITEST_H_

#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

#include <kmdimainfrm.h>

class KMdiChildView;

/**
 * @short Application Main Window
 * @author Andrea Bergia <andreabergia2@yahoo.it>
 * @version 0.1
 */
class KMDITest : public KMdiMainFrm
{
    Q_OBJECT
public:
    /**
     * Default Constructor
     */
    KMDITest();

    /**
     * Default Destructor
     */
    virtual ~KMDITest();
    
protected slots:
    void openNewWindow();
    
    void currentChanged( KMdiChildView *current );
    
    void closeCurrent();
    
protected:
    unsigned m_childs;
};

#endif // _KMDITEST_H_
