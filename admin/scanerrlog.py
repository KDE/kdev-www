#! /usr/bin/env python
# scanerrlog - (c) 2000-2002 Jerome Alet & Free Software Foundation
#
# You're welcome to redistribute this software under the
# terms of the GNU General Public Licence version 2.0
# or, at your option, any higher version.
#
# You can read the complete GNU GPL in the file COPYING
# which should come along with this software, or visit
# the Free Software Foundation's WEB site http://www.fsf.org
#
# author: Jerome Alet - <alet@librelogiciel.com>
#
# $Id: scanerrlog.py,v 1.1 2004/12/14 15:10:01 aclu Exp $
#
# $Log: scanerrlog.py,v $
# Revision 1.1  2004/12/14 15:10:01  aclu
# Improved the apache error_log analysis
#
# Revision 1.60  2002/05/01 21:33:15  jerome
# Homepage moved
#
# Revision 1.59  2002/05/01 21:06:32  jerome
# Version number changed to 2.01
# Copyright strings updated
#
# Revision 1.58  2001/04/23 15:21:33  jerome
# Reference to jaxml-2.22 was added.
#
# Revision 1.57  2001/04/23 12:11:41  jerome
# Error with the new jaxml API.
#
# Revision 1.56  2001/04/23 11:52:24  jerome
# Now works fine with ReportLab 1.06 and doesn't need jahtml anymore.
#
# Revision 1.55  2001/02/20 11:05:41  jerome
# Version number changed to 2.00
# Documentation modified to reflect the need of the jaxml-2.xx module and
# the fact that we don't need the jahtml module anymore.
# Modified the source to use jaxml instead of jahtml for the HTML and CGI
# reports.
# Modified the source to take care of the new ReportLab API, but doesn't work
# yet.
#
# Revision 1.54  2000/12/01 07:50:24  jerome
# Missing "import os" line caused program to crash under Solaris (at least)
# Version changed to 1.9
#
# Revision 1.53  2000/11/30 14:32:07  jerome
# Bug fix: reading error messages from stdin on a second pass using the --continue option
# caused an illegal seek under some circumstances.
#
# Revision 1.52  2000/11/23 14:34:56  jerome
# typo
#
# Revision 1.51  2000/11/23 14:22:18  jerome
# Removed debug stuff.
# Added a warning on the -c | --continue option incompatibility with
# parsing multiple files.
#
# Revision 1.50  2000/11/23 14:11:34  jerome
# The possibility to use the continue flag in the CGI form is documented.
#
# Revision 1.49  2000/11/23 14:02:48  jerome
# The -c | --continue option seems to work.
#
# Revision 1.48  2000/11/16 10:53:09  jerome
# We don't want to load the whole file into memory, so we proceed 100000 lines
# at a time.
#
# Revision 1.47  2000/11/16 10:38:54  jerome
# Version number changed to 1.7
#
# Revision 1.46  2000/11/16 10:20:12  jerome
# error_log file reading optimisations.
#
# Revision 1.45  2000/11/12 22:01:30  jerome
# Paragraph splitting seems to be OK now.
#
# Revision 1.44  2000/11/10 21:37:44  jerome
# Paragraphs splitting seem to almost work
#
# Revision 1.43  2000/11/10 14:08:54  jerome
# Passage en version 1.6
# Modifications pour couper correctement les paragraphes comportant des
# url trop longues: a terminer de coder.
#
# Revision 1.42  2000/09/29 13:39:10  jerome
# Sample reports are not distributed anymore, please use the online test.
# The jahtml module is not distributed anymore as part of ScanErrLog.
# ScanErrLog now uses (needs) the jaxml module.
# Version changed to 1.5
#
# Revision 1.41  2000/09/08 11:37:29  jerome
# Minor change to the XML reports
# A nice file name is proposed by the browser when used as a CGI script
# < and > are replaced with their equivalent SGML entities when it might
# create problems.
#
# Revision 1.40  2000/09/08 05:21:04  jerome
# Now the browser proposes a correct filename when used as a CGI script.
#
# Revision 1.39  2000/07/13 12:20:34  jerome
# Minor documentation changes
#
# Revision 1.38  2000/07/13 12:15:49  jerome
# PDF support finalise
# Version 1.3 OK
#
# Revision 1.37  2000/07/12 15:18:52  jerome
# Ca marche avec la derniere version de reportlab.
# il reste a tronquer les messages trop longs pour le tableau en pdf.
# il reste a corriger le format xml.
# il reste a simplifier le constructeur de chacun des formats.
# et apres, c'est fini !!!
#
# Revision 1.36  2000/07/11 21:39:28  jerome
# Preparation pour nouvelle version de reportlab
#
# Revision 1.35  2000/06/20 13:28:47  jerome
# Some minor modifications in ApacheErrLog.__repr__()
# because it couldn't work (still untested but should work now).
#
# Revision 1.34  2000/06/15 10:21:52  jerome
# Begining of the extract of all reports from the ApacheErrLog class
#
# Revision 1.33  2000/06/07 08:12:43  jerome
# Generalization of the report generation.
# The next version will extract the reports from the ApacheErrLog class
#
# Revision 1.32  2000/06/06 07:36:36  jerome
# I've added the Log CVS tag to scanerrlog.py
#
#

__mydoc = """ScanErrLog v%s (C) 2000 Free Software Foundation

This Python module allows people to parse Apache error_log files from
one of different possible sources (filename, stdin, python file object),
and present their datas in decreasing number of occurences of error
messages.

This is particularly useful if you want to quickly solve the most
annoying problems web surfers encounter visiting your site.

If you run this module directly, it will parse each file which name was
passed on the command line.

If you don't pass any argument on the command line, then scanerrlog will
read an error_log from stdin if you've piped some file or command to its
standard input, or it will print its documentation if you've not.

You can also use it as a CGI script, but you'll not be able to
modify the pattern and outputfile used, and the input filename
should not begin with / or contain .. in its name, all for
security reasons. The names you may use for your CGI variables
are: continue, date, withoutheader, title, limit, exclude, format and
inputfile.
if continue, date or withoutheader exist in your form, these options
will be set to TRUE whatever value they have. See ScanErrLog.html for
a sample form to launch ScanErrLog as a CGI script.


e.g.:

    ./scanerrlog.py

prints scanerrlog's documentation (what you are reading now)


    ./scanerrlog.py /var/log/httpd/error_log /var/log/httpd/error_log.1

will read datas from the specified files.


    ./scanerrlog </var/log/httpd/error_log

will read datas from standard input

You can pass some options on the command line:

options:
        -c | --continue         useful if you want to parse the same file
                                many times (e.g. every week): the current
                                state and statistics of the file are saved
                                in a file named %s in the
                                same directory, so you don't have to reparse
                                the beginning of the file each time. You
                                should use this option either to tell
                                ScanErrLog to save the statistics or to reuse
                                the saved ones.
                                Without this option the file is completely
                                parsed again, even if you've got an old
                                statistics file saved in the same directory.
                                WARNING: this option is incompatible with
                                the parsing of multiple files.
        -d | --date             include in the final report the date when
                                each message appeared for the last time.
                                this option is mutually exclusive with
                                the --pattern option.
        -e | --exclude e        e is a slash separated list of
                                messages severity. All messages with
                                a severity listed in e are excluded
                                from the final report. By default all
                                messages are included. For example,
                                e can be: info/debug to exclude all
                                messages which severity is info or
                                debug.
        -f | --format f         output format for the report, f can be
                                any of:
                                    %s
                                the default format is %s.
        -h | --help             displays this help screen.
        -l | --limit lim        selects messages only if their number of
                                occurences equals or exceeds lim.
                                lim's default value is 1, meaning all
                                messages are included in the final report.
        -n | --nocumulate       don't cumulate counts for all the files
                                passed on the command line. the old
                                -c | --cumulate option is now the default.
                                if the following option -o is not used,
                                then -n implies -w because all reports
                                will be in the same file (stdout).
        -o | --outputfile f     save the report in the file f.
                                if -n is used, then the filename will
                                be n.f where n is an integer incremented
                                for each new file and starting at 1.
        -p | --pattern regexp   select only the lines which match regexp.
                                the default regexp is:
        %s
                                which selects all Apache logged messages,
                                but not errors from CGI scripts for example.
                                to work correctly, your regexp should consume
                                all characters from the beginning of the
                                error line up to the beginning of the real
                                error message.
                                this option is mutually exclusive with
                                the --date option.
        -t | --title t          sets the report title.
        -v | --version          displays ScanErrLog's version number.
        -w | --withoutheader    suppress the header of the HTML report.
                                useful if you want to include the report
                                directly into another HTML document.


Warning: some options may not work with all report formats.

A fifth possibility is to import this module into another python
program and use the ApacheErrorLog class it defines.

ScanErrLog comes with ABSOLUTELY NO WARRANTY
This is free software, and you are welcome to redistribute it under
certain conditions; refer to the Gnu General Public License for details.
You'll find the GNU GPL in the file COPYING which should came along
with this software or at http://www.gnu.org

Please e-mail bugs to: %s (Jerome Alet)"""

__version__ = "2.01"

#
# This was my very first regular expression use, RIP ;-)
# SEL_DEFAULTPATTERN = "^httpd: [[](.+)[]] [[](.+)[]] [[](.+)[]]"

#
# This New Version
# Should handle "[date] [severity] [client] message"
# as well as    "[date] [severity] message"
# and           "httpd: [date] [severity] etc..."
# but maybe something faster exists
# SEL_DEFAULTPATTERN = "^.*(\[(.+)\] ){1,3}"
#
# and this new one should allow me to retreive all the fields
# but maybe something simpler exists
SEL_DEFAULTPATTERN = r"^(httpd: |\B)\[([^\[\]]+)\] \[([^\[\]]+)\] (?:\[([^\[\]]+)\] )?"

#
# by default all messages are included
SEL_DEFAULTLIMIT = 1

#
# no continuation is done
SEL_DEFAULTCONTINUED = 0

#
# and no dates are included
SEL_DEFAULTWITHDATE = 0

#
# Default title for the HTML report
SEL_DEFAULTTITLE = "ScanErrLog v%s Report" % __version__

#
# Default filename for HTML report
SEL_DEFAULTFILENAME = "-"   # stdout

#
# Statistics filename
SEL_STATSFILENAME = "ScanErrLog.stats"

#
# Do we want the HTML headers to appear in the report ?
SEL_DEFAULTWITHHEADER = 1   # we want the HTML header

#
# Default format for output report
SEL_DEFAULTFORMAT = "html"
SEL_MIMETYPES = { "html" : ("text/html", "attachment; filename=report.html"), "text" : ("text/plain", "attachment; filename=report.txt"), "xml" : ("text/xml", "attachment; filename=report.xml") , "pdf" : ("application/pdf", "attachment; filename=report.pdf") }

#
# Default exclusion string
SEL_DEFAULTEXCLUDE = ""

#
# some colors for the HTML report
SEL_DEFAULTTEXTCOLOR = "black"
SEL_DEFAULTBGCOLOR = "white"
SEL_DEFAULTTHCOLOR = "skyblue"
SEL_DEFAULTTDCOLOR = "beige"

# maintainer's home page
SEL_HOMEPAGE = "http://www.librelogiciel.com/software/"

# maintainer's email
SEL_EMAIL = "alet@librelogiciel.com"

__doc__ = __mydoc % (__version__, SEL_STATSFILENAME, repr(SEL_MIMETYPES.keys())[1:-1], repr(SEL_DEFAULTFORMAT), SEL_DEFAULTPATTERN, SEL_EMAIL)

import sys
import os
import string
import time
import re
import getopt
import cgi
import cStringIO
import cPickle

try :
        import jaxml
except ImportError, msg :
        sys.stderr.write("%s\n\n" % msg)
        sys.stderr.write("ScanErrLog: It seems you lack the jaxml python module.\n")
        sys.stderr.write("This module which handles XML file generation is now necessary for ScanErrLog to work correctly.\n")
        sys.stderr.write("You can download jaxml from %s\n" % SEL_HOMEPAGE)
        sys.exit(-1)

try :
        from reportlab.platypus.paragraph import Paragraph
        from reportlab.platypus.tables import *
        from reportlab.platypus.doctemplate import *
        from reportlab.pdfbase import pdfmetrics
        from reportlab.lib.units import inch,cm
        from reportlab.lib import styles
        _HaveReportLab = 1
except ImportError, msg :
        _HaveReportLab = 0


class ANYReport :
        def __init__(self, filename, title, date, withheader, errlog) :
                self.__filename = filename
                self._withdate = errlog._withdate
                if errlog._unwanted == errlog._linestotal :
                        self._isrejected = "All lines were rejected, maybe the input file wasn't an Apache ErrorLog file.\n"
                else :
                        self._isrejected = ""
                self._errlog = errlog
                self._categories = errlog._prepare()
                self._buildreport(title, date, withheader)

        def _underlimit_msg(self) :
                if self._errlog._underlimit :
                        return "%i Messages with less than %i occurences (%i%%).\n" % (self._errlog._underlimit, self._errlog._limit, self._percent(self._errlog._underlimit, self._errlog._linestotal - self._errlog._unwanted))
                else :
                        return ""

        def _unwanted_msg(self) :
                return "Skipped %i unwanted lines (%i%%).\n" % (self._errlog._unwanted, self._percent(self._errlog._unwanted, self._errlog._linestotal))

        def _linestotal_msg(self) :
                return "%i Lines total." % self._errlog._linestotal

        def _percent(self, val, max) :
                return (val * 100) / max

        def _preformat(self, msg) :
                msg = string.replace(msg, "&", "&amp;")
                msg = string.replace(msg, ">", "&gt;")
                return string.replace(msg, "<", "&lt;")

        def output(self) :
                opened = 0
                if type(self.__filename) == type(sys.stdout) :
                        outf = self.__filename
                elif (self.__filename == None) or (self.__filename == "-") :
                        outf = sys.stdout
                else :
                        outf = open(self.__filename, "w")
                        opened = 1
                outf.write(repr(self))
                outf.flush()
                if opened :
                        outf.close()


class TEXTReport(ANYReport) :
        def __repr__(self) :
                return self.__report

        def _buildreport(self, title, date, withheader) :
                self.__report = title + "\n" + date + "\n"
                if self._isrejected :
                        self.__report = self.__report + self._isrejected
                else :
                        for categorie in self._categories :
                                self.__report = self.__report + self.__formatmessage(categorie["number"], categorie["latestdate"], categorie["message"])
                                for detail in categorie["details"] :
                                        self.__report = self.__report + '\t' + self.__formatmessage(detail["number"], detail["latestdate"], detail["message"])
                                self.__report = self.__report + '\n'
                        self.__report = self.__report + self._underlimit_msg()
                        self.__report = self.__report + self._unwanted_msg()
                        self.__report = self.__report + self._linestotal_msg()

        def __formatmessage(self, cpt, date, msg) :
                """Formats a message for the final report"""
                txt = "%6i " % cpt
                if self._withdate and (date != None) :
                        txt = txt + "(%s) " % date
                return txt + ("=> %s\n" % msg)


class XMLReport(ANYReport, jaxml.XML_document) :
        def __init__(self, filename, title, date, withheader, errlog) :
                jaxml.XML_document.__init__(self)
                ANYReport.__init__(self, filename, title, date, withheader, errlog)

        def _buildreport(self, title, date, withheader) :
                self.report()
                self.title(title)
                self.date(date)
                if self._isrejected :
                        self.error(self._isrejected)
                else :
                        self._push()
                        self.statistics()
                        self._push()
                        self.total()
                        self.value(self._errlog._linestotal)
                        self.percent(100)
                        self._pop()
                        self._push()
                        self.skipped()
                        self.value(self._errlog._unwanted)
                        self.percent(self._percent(self._errlog._unwanted, self._errlog._linestotal))
                        self._pop()
                        self.belowlimit()
                        self.value(self._errlog._underlimit)
                        self.percent(self._percent(self._errlog._underlimit, self._errlog._linestotal - self._errlog._unwanted))
                        self._pop()

                        self._push()
                        self.details()
                        for categorie in self._categories :
                                self._push()
                                self.message(count = categorie["number"])
                                self.text(self._preformat(categorie["message"]))
                                if self._withdate and (categorie["latestdate"] != None) :
                                        self.latestdate(categorie["latestdate"])
                                if categorie["details"] :
                                        for submessage in categorie["details"] :
                                                self._push()
                                                self.message(count = submessage["number"])
                                                self.text(self._preformat(submessage["message"]))
                                                if self._withdate and (submessage["latestdate"] != None) :
                                                        self.latestdate(submessage["latestdate"])
                                                self._pop()
                                self._pop()
                        self._pop()

                self.software()
                self.name("ScanErrLog")
                self.version(__version__)
                self.home("%s" % SEL_HOMEPAGE)
                self.license("GNU GPL")
                self._push()
                self.maintainer()
                self.name("J&eacute;r&ocirc;me Alet")
                self.email("%s" % SEL_EMAIL)
                self._pop()


class HTMLReport(ANYReport, jaxml.HTML_document) :     # we want ANYReport.output method to override jaxml.HTML_document's one
        def __init__(self, filename, title, date, withheader, errlog) :
                # but here we want to initialise jaxml.HTML_document first, ugly isn't it ?
                jaxml.HTML_document.__init__(self)
                ANYReport.__init__(self, filename, title, date, withheader, errlog)

        #
        # jahtml.Html_document can handle many more things than ANYReport,
        # but we still make HTMLReport a subclass of ANYReport in order to
        # make all reports class look the same.
        def _buildreport(self, title, date, withheader) :
                if withheader :
                        self._default_header(title, keywords = "ScanErrLog, Report, Apache, GNU, GPL")
                        self.body(text = SEL_DEFAULTTEXTCOLOR, bgcolor = SEL_DEFAULTBGCOLOR)

                self.h2(title)
                self.h3(date)

                if self._isrejected :
                        self.p(self._isrejected)
                else :
                        self.h4("Statistics:")
                        underlimit = self._underlimit_msg()
                        if underlimit :
                                self._text(underlimit)
                                self._br()
                        self._text(self._unwanted_msg())
                        self._br()
                        self._text(self._linestotal_msg())

                        self._push()
                        self.h4("Details:")
                        self.ul()
                        for categorie in self._categories :
                                self._push()
                                self.p()
                                self.li()
                                self.strong(self._preformat(categorie["message"]))
                                self._br()
                                self._text("%i times." % categorie["number"])
                                if self._withdate and (categorie["latestdate"] != None) :
                                        self._br()
                                        self._text("Last time: " + categorie["latestdate"])
                                if categorie["details"] :
                                        self._br()
                                        self.table(border = 2)
                                        self._push()
                                        self.tr()
                                        self.th("Message", align = "center", bgcolor = SEL_DEFAULTTHCOLOR)
                                        if self._withdate :
                                                self.th("Last time", align = "center", bgcolor = SEL_DEFAULTTHCOLOR)
                                        self.th("Times", align = "center", bgcolor = SEL_DEFAULTTHCOLOR)
                                        self._pop()
                                        for detail in categorie["details"] :
                                                self._push()
                                                self.tr()
                                                self.td(self._preformat(detail["message"]), align = "left", bgcolor = SEL_DEFAULTTDCOLOR)
                                                if self._withdate :
                                                        if detail["latestdate"] != None :
                                                                self.td(detail["latestdate"], align = "center", bgcolor = SEL_DEFAULTTDCOLOR)
                                                        else :
                                                                self.td("&nbsp;", bgcolor = SEL_DEFAULTTDCOLOR)
                                                self.td(detail["number"], align = "right", bgcolor = SEL_DEFAULTTDCOLOR)
                                                self._pop()
                                self._pop()
                        self._pop()

                self._hr(width = "33%", noshade = "noshade")
                self._push()
                self.table(border=0, width="100%")
                self.tr()
                self._push()
                self.td(align = "left")
                self._text("report generated by ")
                self.a("ScanErrLog v%s" % __version__, href = "%s" % SEL_HOMEPAGE)
                self._pop()
                self.td(align="right")
                self._text("email bugs to: ")
                self.a("%s" % SEL_EMAIL, href = "mailto:%s" % SEL_EMAIL)
                self._pop()
                self._hr(height = 2, width = "100%", noshade = "noshade")


if _HaveReportLab :
        class PDFReport(ANYReport, BaseDocTemplate) :
                class MyPageTemplate(PageTemplate) :
                        def __init__(self, parent, title, date) :
                                self.__title = title
                                self.__date = date
                                myFrame = Frame(1 * inch, inch, parent.pagesize[0] - 1.5 * inch, parent.pagesize[1] - (2 * inch), showBoundary = 0)
                                PageTemplate.__init__(self, 'First', [myFrame])

                        def beforeDrawPage(self, canvas, doc) :
                                canvas.saveState()
                                canvas.setFont('Helvetica', 16)
                                canvas.drawString(inch, doc.pagesize[1] - (0.75 * inch), "%s" % self.__title)
                                canvas.setFont('Helvetica', 14)
                                canvas.drawRightString(doc.pagesize[0] - (0.5 * inch), doc.pagesize[1] - (0.75 * inch), "%s" % self.__date)
                                canvas.setFont('Times-Roman', 10)
                                canvas.drawCentredString((doc.pagesize[0] / 2) + (0.5 * inch), 0.75 * inch, "Page %d" % canvas.getPageNumber())
                                canvas.setStrokeColorRGB(1,0,0)
                                canvas.setLineWidth(5)
                                canvas.line(0.75 * inch, inch, 0.75 * inch, doc.pagesize[1] - inch)
                                canvas.restoreState()

                def __init__(self, filename, title, date, withheader, errlog) :
                        self.__objects = []
                        self.__report = cStringIO.StringIO()
                        self.__built = 0
                        BaseDocTemplate.__init__(self, self.__report)
                        self.addPageTemplates(self.MyPageTemplate(self, title, date))
                        ANYReport.__init__(self, filename, title, date, withheader, errlog)

                def __repr__(self) :
                        if self.__built :
                                return self.__report.getvalue()
                        else :
                                return None

                def append(self, object) :
                        self.__objects.append(object)

                def parasplit(self, msg, style, width) :
                        """This method splits a paragraph according to the current cell width
                           and indicates (replaces) strings that were cut by (...) in red color"""
                        cutmsg = """<font color="red">(...)</font>"""
                        wordslist = []
                        p = Paragraph(msg, style)
                        (w,h) = p.wrap(width, 100000)   # set how it fits in the current width
                        cut = 0
                        for frag in p.blPara.lines:
                                # get the remaining space on the line
                                if hasattr(frag, "extraSpace") and hasattr(frag, "words") :
                                        extraSpace, words = frag.extraSpace, frag.words
                                else :
                                        extraSpace, words = frag

                                # if larger than the cell width, then RL doesn't know how to split it
                                if (p.width - extraSpace) > width :
                                        wordslist.append(cutmsg)
                                        cut = 1
                                else :
                                        # normal case: all frags fit on the line
                                        for w in words :
                                                if hasattr(w, "text") :
                                                        wordslist.append(w.text)
                                                else :
                                                        wordslist.append(w)
                        if not cut :
                                msg = self._preformat(string.join(wordslist, " "))
                        else :
                                # since self._preformat escapes &, < and >
                                # we should build our message manually
                                msg = ""
                                part = ""
                                for word in wordslist :
                                        if word != cutmsg :
                                                part = part + " " + word
                                        else :
                                                msg = msg + self._preformat(part) + " " + cutmsg
                                                part = ""
                                msg = msg + self._preformat(part)
                        return Paragraph(msg, style)

                def _buildreport(self, title, date, withheader) :
                        myStyleSheet = styles.getSampleStyleSheet()
                        myTStyle = \
                                [ ('BACKGROUND', (0, 0), (-1, 0), SEL_DEFAULTTHCOLOR),
                                  ('BACKGROUND', (0, 1), (-1, -1), SEL_DEFAULTTDCOLOR),
                                  ('TEXTCOLOR', (0, 0), (-1, -1), SEL_DEFAULTTEXTCOLOR),
                                  ('INNERGRID', (0, 0), (-1, -1), 0.25, 'BLACK'),
                                  ('BOX', (0, 0), (-1, -1), 2, 'BLACK'),
                                  ('BOX', (0, 0), (-1, 0), 2, 'BLACK'),
                                  ('BOX', (0, 0), (0, -1), 2, 'BLACK'),
                                  ('BOX', (-1, 0), (-1, -1), 2, 'BLACK'),
                                  ('ALIGN', (0, 0), (-1, 0), 'CENTER'),
                                  ('ALIGN', (0, 1), (0, -1), 'LEFT'),
                                  ('ALIGN', (-1, 1), (-1, -1), 'RIGHT'),
                                  ('FONT', (0, 0), (-1, 0), 'Times-Bold', 12)
                                ]
                        if self._withdate :
                                myTStyle.append(('ALIGN', (1, 1), (1, -1), 'CENTER'))
                        myTableStyle = TableStyle(myTStyle)

                        part_style = myStyleSheet['Heading2']
                        cat_style = myStyleSheet['Heading3']
                        normal_style = myStyleSheet['Normal']
                        if self._isrejected :
                                self.append(Paragraph(self._isrejected, normal_style))
                        else :
                                self.append(Paragraph("Statistics:", part_style))
                                underlimit = self._underlimit_msg()
                                if underlimit :
                                        self.append(Paragraph(underlimit, normal_style))
                                self.append(Paragraph(self._unwanted_msg(), normal_style))
                                self.append(Paragraph(self._linestotal_msg(), normal_style))
                                self.append(Paragraph("Details:", part_style))
                                for categorie in self._categories :
                                        self.append(Paragraph(self._preformat(categorie["message"]), cat_style))
                                        self.append(Paragraph("%i times." % categorie["number"], normal_style))
                                        if self._withdate and (categorie["latestdate"] != None) :
                                                self.append(Paragraph("Last time: " + categorie["latestdate"], normal_style))
                                        if categorie["details"] :
                                                entete = [Paragraph('Message', normal_style), Paragraph('Times', normal_style)]
                                                colwidths = [ 6 * inch, 0.5 * inch ]
                                                rowheights = [18]
                                                if self._withdate :
                                                        entete.insert(1, Paragraph('Last time', normal_style))
                                                        lasttime_width = 1.75 * inch
                                                        colwidths[0] = colwidths[0] - lasttime_width
                                                        colwidths.insert(1, lasttime_width)
                                                data = []
                                                data.append(tuple(entete))
                                                for detail in categorie["details"] :
                                                        # we use flowable cells to handle very long lines.
                                                        # but RL sometimes can't split things.
                                                        # so we have to do something by ourselves
                                                        # 12 padding points
                                                        row = [ self.parasplit(self._preformat(detail["message"]), normal_style, colwidths[0] - 12) ]
                                                        if self._withdate :
                                                                if detail["latestdate"] != None :
                                                                        row.append(detail["latestdate"])
                                                                else :
                                                                        row.append("")
                                                        row.append(detail["number"])
                                                        data.append(tuple(row))
                                                        rowheights.append(None) # automatic height
                                                mytable = Table(tuple(data), colwidths, rowheights, repeatRows=1)
                                                mytable.setStyle(myTableStyle)
                                                self.append(Spacer(0, 10))
                                                self.append(mytable)
                        self.build(self.__objects)
                        self.__built = 1


class ApacheErrorLog :
        """This class formats an Apache error_log file, sorting it 
in decreasing order of error messages occurences"""        

        def __init__(self, file = None, pattern = SEL_DEFAULTPATTERN, limit = SEL_DEFAULTLIMIT, withdate = SEL_DEFAULTWITHDATE, exclude = SEL_DEFAULTEXCLUDE, continued = SEL_DEFAULTCONTINUED) :
                """If file is not None, then it is automatically opened, read and closed.
If you don't pass a file argument then you'll have to manually open, read and close your own file.
file can be: None, a python file object, a filename, or "-" for sys.stdin

pattern is a regular expression, its default value allows to select all of
Apache "normal" error_log messages, but you can specify your own selection pattern.

limit is an integer or string of digits representing an integer. It allows people
to decide to exclude messages which number of occurences is less than limit
from the final report.

withdate is a flag. If set then the latest message apparitions dates will be included
in the final report.

exclude is string representing a slash separated list of messages severity to exclude from
the final report, e.g.: notice/info/debug will exclude all messages which severity is
notice or info or debug.

continued is a flag telling if statistics have to be saved for further reuse and/or have to
be reused.
"""             
                self.__continued = continued
                self.__erreurs = {}
                self._linestotal = 0
                self._unwanted = 0
                self._underlimit = 0
                self.__infile = None
                self.__opened = 0
                self.__read = 0
                self.__pattern = re.compile(pattern)
                self._withdate = withdate
                self.__exclude = map(string.strip, string.split(exclude, '/'))
                if withdate :
                        if pattern != SEL_DEFAULTPATTERN :
                                self.__stderrmessage(["ScanErrLog: user defined regexp and dates inclusion are mutually exclusive options.\n", \
                                                      "Your regexp will be used but no date will be included in the final report.\n"])
                                self._withdate = 0
                        elif not hasattr(time, "strptime") :
                                self.__stderrmessage(["ScanErrLog: it seems either your Python distribution or your standard C library doesn't support the strptime() function.\n", \
                                                      "No date will be included in the final report.\n"])
                                self._withdate = 0

                try :
                        self._limit = int(limit)
                except (ValueError, OverflowError) :
                        self.__stderrmessage(["ScanErrLog: incorrect limit [%s] argument, defaulting to %i.\n" % (repr(limit), SEL_DEFAULTLIMIT)])
                        self._limit = SEL_DEFAULTLIMIT

                if file != None :
                        self.open(file)
                        self.read()
                        self.close()

        def __repr__(self) :
                if not self.__read :
                        return "Error logfile [%s] not read" % repr(self.__infile)
                else :
                        return repr(TEXTReport(None, SEL_DEFAULTTITLE, self.__ascdate(time.time()), 0, self))

        def __stderrmessage(self, messages = []) :
                for message in messages :
                        sys.stderr.write(message)
                sys.stderr.flush()

        def __ascdate(self, date) :
                if date != None :
                        return time.asctime(time.localtime(date))

        def __includeseverity(self, line, match) :
                if len(match) > 3 :
                        beg, end = match[3]
                        if line[beg:end] in self.__exclude :
                                return 0
                return 1

        def __getdate(self, line, match) :
                """Tries to get a date from a line, according to the regexp match done"""
                if self._withdate :
                        if len(match) > 2 :
                                datebeg, dateend = match[2]
                                try :
                                        # we do a string.join(string.split(date)) because
                                        # in some cases Apache stores the day number preceded by
                                        # a space if that number is only one digit long (1st to 9th)
                                        # I saw this problem with Apache 1.3.4, RedHat SparcLinux 4.2
                                        (year, month, day, hour, minute, second, weekday, jday, dst) = time.strptime(string.join(string.split(line[datebeg:dateend])))
                                        return time.mktime((year, month, day, hour, minute, second, weekday, jday, -1))
                                except ValueError,msg :
                                        self.__stderrmessage(["ScanErrLog: %s\n" % msg, \
                                                              "date was [%s]\n" % date])

        def __append(self, numericdate, errorline) :
                """Append each line of the error_log file to the internal structure"""
                erreur = string.split(errorline, ':')
                errmsg = string.strip(erreur[0])
                reste = string.strip(string.join(erreur[1:], ':'))
                if self.__erreurs.has_key(errmsg) :
                        compteur, nmdate, messages = self.__erreurs[errmsg]
                        if reste :
                                if messages.has_key(reste) :
                                        cpt, mdate = messages[reste]
                                        if (numericdate != None) and (mdate < numericdate) :
                                                mdate = numericdate     # we keep latest date
                                        messages[reste] = (cpt + 1, mdate)
                                else :
                                        messages[reste] = (1, numericdate)
                        if (numericdate != None) and (nmdate < numericdate) :
                                nmdate = numericdate    # we keep latest date
                        self.__erreurs[errmsg] = (compteur + 1, nmdate, messages)
                else :
                        if reste :
                                self.__erreurs[errmsg] = (1, numericdate, {reste : (1, numericdate)})
                        else :
                                self.__erreurs[errmsg] = (1, numericdate, { })

        def _prepare(self) :
                """Sort all the things in the right order"""
                k = self.__erreurs.keys()
                k.sort(lambda x, y, l = self.__erreurs : cmp(l[y], l[x]))
                categories = []
                for i in k :
                        cpt, ld, messages = self.__erreurs[i]
                        if cpt >= self._limit :
                                km = messages.keys()
                                km.sort(lambda x, y, l = messages : cmp(l[y], l[x]))
                                catmsgs = []
                                for im in km :
                                        compteur, latestdate = messages[im]
                                        catmsgs.append({ "message" : im, "latestdate": self.__ascdate(latestdate), "number" : compteur })
                                categories.append({ "message" : i, "latestdate" : self.__ascdate(ld), "number" : cpt, "details" : catmsgs})
                        else :
                                self._underlimit = self._underlimit + cpt
                return categories

        def pdfreport(self, title = SEL_DEFAULTTITLE, outputfile = SEL_DEFAULTFILENAME, withheader = 0) : # no header needed
                """Outputs the report in pdf format"""
                if not self.__read :
                        self.__stderrmessage("Error logfile [%s] not read" % repr(self.__infile))
                else :
                        if _HaveReportLab :
                                PDFReport(outputfile, title, self.__ascdate(time.time()), withheader, self).output()
                        else :
                                self.__stderrmessage(["ScanErrLog: It seems you lack the ReportLab python module.\n", \
                                        "This module which handles PDF file generation is now necessary for ScanErrLog to work correctly.\n", \
                                        "You can download ReportLab from http://www.reportlab.com\n", \
                                        "However, any ReportLab version older than July 13th 2000 will not work.\n" ])

        def htmlreport(self, title = SEL_DEFAULTTITLE, outputfile = SEL_DEFAULTFILENAME, withheader = SEL_DEFAULTWITHHEADER) :
                """Outputs the report in html format"""
                if not self.__read :
                        self.__stderrmessage("Error logfile [%s] not read" % repr(self.__infile))
                else :
                        HTMLReport(outputfile, title, self.__ascdate(time.time()), withheader, self).output()

        def textreport(self, title = SEL_DEFAULTTITLE, outputfile = SEL_DEFAULTFILENAME, withheader = 0) : # no header needed
                """Outputs the report in text format"""
                if not self.__read :
                        self.__stderrmessage("Error logfile [%s] not read" % repr(self.__infile))
                else :
                        TEXTReport(outputfile, title, self.__ascdate(time.time()), withheader, self).output()

        def xmlreport(self, title = SEL_DEFAULTTITLE, outputfile = SEL_DEFAULTFILENAME, withheader = 0) : # no header needed
                """Outputs the report in xml format"""
                if not self.__read :
                        self.__stderrmessage("Error logfile [%s] not read" % repr(self.__infile))
                else :
                        XMLReport(outputfile, title, self.__ascdate(time.time()), withheader, self).output()

        def cgireport(self, title = SEL_DEFAULTTITLE, format = SEL_DEFAULTFORMAT, withheader = SEL_DEFAULTWITHHEADER) :
                """Outputs the report as a CGI output"""
                #
                # we do this test here too, to be able to catch
                # the problem and show it into the user's browser
                # before the underlying function do it to stderr only.
                if not self.__read :
                        _cgi_error("Error logfile [%s] not read" % repr(self.__infile))
                else :
                        format = string.lower(format)
                        if format not in SEL_MIMETYPES.keys() :
                                _cgi_error("Incorrect [%s] format value. This variable should be one of %s" % (format, repr(SEL_MIMETYPES.keys())))
                        else :
                                (ctype, cdisp) = SEL_MIMETYPES[format]
                                doc = jaxml.CGI_document(content_type = ctype)
                                if cdisp :
                                        doc._set_content_disposition(cdisp)
                                doc._output(SEL_DEFAULTFILENAME)
                                getattr(self, format + "report")(title, SEL_DEFAULTFILENAME, withheader)

        def open(self, file) :
                """Opens an Apache error_log file and skip the first lines if in 'continue' mode"""
                self.__opened = 0
                self.__statsfilename = SEL_STATSFILENAME
                if type(file) == type(sys.stdin) :
                        self.__infile = file
                elif (file == None) or (file == "-") :
                        self.__infile = sys.stdin
                else :
                        self.__infile = open(file, "r")
                        self.__statsfilename = os.path.join(os.path.dirname(file), SEL_STATSFILENAME)
                        self.__opened = 1

                if self.__continued :
                        try :
                                pickin = open(self.__statsfilename, "r")
                                self.__erreurs = cPickle.load(pickin)
                                self._linestotal = cPickle.load(pickin)
                                self._unwanted = cPickle.load(pickin)
                                self._underlimit = cPickle.load(pickin)
                                alreadyread = cPickle.load(pickin)
                                pickin.close()

                                try :
                                        self.__infile.seek(alreadyread, 0)
                                except IOError :
                                        pass    # probably an invalid or illegal seek, e.g. stdin
                        except IOError :
                                self.__erreurs = {}
                                self._linestotal = 0
                                self._unwanted = 0
                                self._underlimit = 0

        def read(self) :
                """Reads an apache error_log file into the internal structure"""
                while 1 :
                        lignes = self.__infile.readlines(100000)
                        if not lignes :
                                break
                        self._linestotal = self._linestotal + len(lignes)
                        for ligne in lignes :
                                 # Then we eat the End Of Line
                                 # we do the test in case of incomplete lines (cf. Python Library Reference)
                                 if ligne[-1] == '\n' :
                                         ligne = ligne[:-1]

                                 #
                                 # And we skip unwanted lines
                                 ok = self.__pattern.match(ligne)
                                 if ok and self.__includeseverity(ligne, ok.regs) :
                                         # and keep only the date and end of normal ones
                                         self.__append(self.__getdate(ligne, ok.regs), ligne[ok.end():])
                                 else :
                                         self._unwanted = self._unwanted + 1
                self.__read = 1

        def close(self) :
                """Optionally save stats and unconditionally closes the Apache error_log file"""
                # save the statistics
                if self.__continued :
                        pickout = open(self.__statsfilename, "w")
                        cPickle.dump(self.__erreurs, pickout)
                        cPickle.dump(self._linestotal, pickout)
                        cPickle.dump(self._unwanted, pickout)
                        cPickle.dump(self._underlimit, pickout)
                        cPickle.dump(self.__infile.tell(), pickout)     # already read size
                        pickout.close()

                # then close the file
                if self.__opened :
                        self.__infile.close()



def __display_version() :
        print __version__
        sys.exit(0)

def __display_usage() :
        print __doc__
        sys.exit(0)

def _cgi_error(message) :
        title = "ScanErrLog v%s Error" % __version__
        doc = jaxml.CGI_document()
        doc._default_header(title)
        doc.body()
        doc.h2(title)
        doc._text(message)
        doc._output()
        sys.stderr.write("%s - %s\n" % (title, message))
        sys.stderr.flush()
        sys.exit(0)

if __name__ == "__main__" :
        do_version = do_usage = 0
        continued = 0
        nocumulate = 0
        pattern = SEL_DEFAULTPATTERN
        limit = SEL_DEFAULTLIMIT
        withdate = SEL_DEFAULTWITHDATE
        withheader = SEL_DEFAULTWITHHEADER
        outputfile = SEL_DEFAULTFILENAME
        title = SEL_DEFAULTTITLE
        format = SEL_DEFAULTFORMAT
        exclude = SEL_DEFAULTEXCLUDE

        form = cgi.FieldStorage()
        if form :       # we are a CGI script
                if form.has_key("title") :
                        title = form["title"].value

                if form.has_key("continue") :
                        continued = 1

                if form.has_key("date") :
                        withdate = 1

                if form.has_key("withoutheader") :
                        withheader = 0

                if form.has_key("limit") :
                        limit = form["limit"].value

                if form.has_key("exclude") :
                        exclude = form["exclude"].value

                if form.has_key("format") :
                        format = form["format"].value

                if form.has_key("inputfile") :
                        inputfile = form["inputfile"].value
                        if (inputfile[0] == '/') or (string.find(inputfile, "../") != -1) :
                                _cgi_error("Incorrect [%s] inputfile value. This variable should not begin with / nor contain ../" % inputfile)

                ApacheErrorLog(inputfile, pattern, limit, withdate, exclude, continued).cgireport(title, format, withheader)

        else :          # normal command line launch
                try:
                        #
                        # parse the command line
                        options,args = getopt.getopt(sys.argv[1:], "cde:f:hl:no:p:t:vw", ["continue", "date", "exclude=", "format=", "help", "limit=", "nocumulate", "outputfile=", "pattern=", "title=", "version", "withoutheader"])
                        if options :
                                for (o, v) in options :
                                        if (o == '-c') or (o == '--continue') :
                                                continued = 1
                                        elif (o == '-d') or (o == '--date') :
                                                withdate = 1
                                        elif (o == '-e') or (o == '--exclude') :
                                                exclude = v
                                        elif (o == '-f') or (o == '--format') :
                                                format = string.lower(v)
                                                if format not in SEL_MIMETYPES.keys() :
                                                        format = SEL_DEFAULTFORMAT
                                        elif (o == '-l') or (o == '--limit') :
                                                limit = v
                                        elif (o == '-n') or (o == '--nocumulate') :
                                                nocumulate = 1
                                        elif (o == '-o') or (o == '--outputfile') :
                                                outputfile = v
                                        elif (o == '-p') or (o == '--pattern') :
                                                pattern = v
                                        elif (o == '-h') or (o == '--help'):
                                                do_usage = 1
                                        elif (o == '-t') or (o == '--title') :
                                                title = v
                                        elif (o == '-v') or (o == '--version') :
                                                do_version = 1
                                        elif (o == '-w') or (o == '--withoutheader') :
                                                withheader = 0
                        elif (not args) and sys.stdin.isatty() : # no option and no argument, we display help if we are a tty
                                do_usage = 1
                except getopt.error,msg :
                        sys.stderr.write("%s\n" % msg)
                        do_usage = 1

                if do_usage :
                        __display_usage()
                elif do_version :
                        __display_version()
                elif args :
                        if len(args) > 1 :
                                sys.stderr.write("ScanErrLog: more than one input file is incompatible with the -c | --continue option. Option -c | --continue disabled.\n")
                                continued = 0
                        if not nocumulate :
                                log = ApacheErrorLog(pattern = pattern, limit = limit, withdate = withdate, exclude = exclude, continued = continued)
                                for filename in args :
                                        log.open(filename)
                                        log.read()
                                        log.close()
                                getattr(log, format + "report")(title, outputfile, withheader)
                        else :
                                count = 0
                                for filename in args:
                                        outf = outputfile
                                        if outf != SEL_DEFAULTFILENAME :
                                                count = count + 1
                                                outf = "%i.%s" % (count, outf)
                                        else :
                                                withheader = 0  # we can't output the header/footer because
                                                                # they'd be duplicated in the output file (stdout)
                                        getattr(ApacheErrorLog(filename, pattern, limit, withdate, exclude, continued), format + "report")(title + " (%s)" % filename, outf, withheader)
                else :
                        getattr(ApacheErrorLog("-", pattern, limit, withdate, exclude, continued), format + "report")(title, outputfile, withheader)
