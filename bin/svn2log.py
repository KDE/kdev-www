#!/usr/bin/python
#
# Copyright (c) 2003 The University of Wroclaw.
# Copyright (c) 2003 Michal Moskal <malekith@pld-linux.org>
# Copyright (c) 2005 Amilcar do Carmo Lucas <amilcar kdevelop.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
#    1. Redistributions of source code must retain the above copyright
#       notice, this list of conditions and the following disclaimer.
#    2. Redistributions in binary form must reproduce the above copyright
#       notice, this list of conditions and the following disclaimer in the
#       documentation and/or other materials provided with the distribution.
#    3. The name of the University may not be used to endorse or promote
#       products derived from this software without specific prior
#       written permission.
# 
# THIS SOFTWARE IS PROVIDED BY THE UNIVERSITY ``AS IS'' AND ANY EXPRESS OR
# IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
# OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN
# NO EVENT SHALL THE UNIVERSITY BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
# SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
# TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
# PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
# LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
# NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
# SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
#

import sys
import os
import time

# Python 2.3 is required by PyRSS2Gen
py_version  = sys.version_info
if sys.version_info[0:2] < (2,3):
    sys.stderr.write("Error: Python 2.3 or higher required")
    sys.exit(1)

import re
import getopt
import string
import codecs
import cgi      # required to escape html characters in the changelog text

from xml.utils import qp_xml

# These imports are required by the RSS output
import datetime

try:
    import PyRSS2Gen
except ImportError:
    sys.stderr.write("Please install PyRSS2Gen before running this script\n")
    sys.stderr.write("PyRSS2Gen can be downloaded from: \n")
    sys.stderr.write("http://www.dalkescientific.com/Python/PyRSS2Gen.html\n")

# Please edit the next values if necessary
kill_prefix_rx = None
default_domain = "localhost"
exclude = []
authors_names = { }
authors_emails = { }
reloc = { }
websvn_url = u""
filelist_max_files = 7
max_join_delta = 3 * 60 # 180 seconds (3 minutes)
list_format = False
rss_title = "KDevelop - SVN HEAD commits"
rss_link = "http://www.kdevelop.org/index.html?filename=HEAD/ChangeLog.html"
rss_description = "The latest SVN commits"
rss_language = "en-us"
rss_copyright = "1998-" + datetime.datetime.now().strftime("%Y") + ", The KDevelop team"
rss_ttl = 60
rss_max_items = 10
output_encoding = 'iso-8859-1'

# Please do NOT edit the next values
lf_style = u"\n"
filelist_start_style = u"\t* "
filelist_sep_style = u"\t   "
filelist_end_style = u": "
email_start_style = u"<"
email_sep_style = u"*"
email_end_style = u">"
authors_only = False

date_rx = re.compile(r"^(\d+-\d+-\d+T\d+:\d+:\d+)")

def die(msg):
  sys.stderr.write(msg + "\n")
  sys.exit(1)

def attr(e, n):
  return e.attrs[("", n)]

def has_child(e, n):
  for c in e.children:
    if c.name == n: return 1
  return 0

def child(e, n):
  for c in e.children:
    if c.name == n: return c
  die("<%s> doesn't have <%s> child" % (e.name, n))

def convert_path(n):
  for src in reloc.keys():
    n = string.replace(n, src, reloc[src])
  if kill_prefix_rx != None:
    if kill_prefix_rx.search(n):
      n = kill_prefix_rx.sub("", n)
    else:
      return None
  if n.startswith("/"): n = n[1:]
  if n == "": n = "/"
  for pref in exclude:
    if n.startswith(pref):
      return None
  return n

def convert_fullname(u):
  if authors_names.has_key(u):
    return authors_names[u]
  else:
    return u

def convert_email(u):
  if authors_emails.has_key(u):
    return authors_emails[u]
  else:
    return u

def wrap_text_line(str, pref, width):
  ret = u""
  line = u""
  first_line = True
  for word in str.split():
    if line == "":
      line = word
    else:
      if word == "*":
        word = lf_style + word
      if len(line + u" " + word) > width:
        if first_line:
          ret += line + lf_style
          first_line = False
          line = word
        else:
          ret += pref + line + lf_style
          line = word
      else:
        line += u" " + word
  if first_line:
    ret += line + lf_style
  else:
    ret += pref + line + lf_style
  return ret

def wrap_text(str, pref, width):
  if not list_format:
    return wrap_text_line(str,pref,width)
  else:
    items = re.split(r"\-\s+",str)
    ret = wrap_text_line(items[0],pref,width)
    for item in items[1:]:
      ret += pref + u"- " + wrap_text_line(item,pref+u"  ",width)
    return ret

class Entry:
  global authors_only
  def __init__(self, tm, rev, author, filelist, msg):
    self.tm = tm
    self.beg_tm = tm
    self.committime = unicode(datetime.datetime.fromtimestamp(tm))
    self.rev = rev
    self.beg_rev = rev
    self.author = author.encode(output_encoding, 'replace')
    self.author_fullname = convert_fullname(author).encode(output_encoding, 'replace')
    self.author_email = convert_email(author).encode(output_encoding, 'replace')

    if self.author_fullname:
      try:
        self.author_complete = self.author_fullname + " " + email_start_style + self.author_email.replace("@",email_sep_style) + email_end_style
      except UnicodeDecodeError:
       # we should use self.author_fullname instead of self.author in here, but python is a stupid language
        self.author_complete = self.author + " " + email_start_style + self.author_email.replace("@",email_sep_style) + email_end_style

    self.filelist = filelist
    self.msg = msg

  def join(self, other):
    self.tm = other.tm
    self.rev = other.rev
    self.filelist += other.filelist
    self.msg += other.msg

  def can_join(self, other):
    return self.author == other.author and abs(self.tm - other.tm) < max_join_delta

  def dump(self, out):
    if authors_only == False:
      if websvn_url == "":
        ref_rev = self.rev
        ref_beg_rev = self.beg_rev
      else:
        ref_rev = '<a href="' + websvn_url + '?rev=' + self.rev + '&amp;view=rev">' + self.rev + '</a>'
        ref_beg_rev = '<a href="' + websvn_url + '?rev=' + self.beg_rev + '&amp;view=rev">' + self.beg_rev + '</a>'
      sys.stderr.write(self.rev+"\n")
      out.write(time.strftime("%Y-%m-%d %H:%M +0000", time.localtime(self.beg_tm)))
      if self.rev != self.beg_rev:
        out.write(" [r%s-%s]  " % (ref_rev, ref_beg_rev))
      else:
        out.write(" [r%s]  " % (ref_rev))
      out.write(self.author_complete + lf_style + self.filelist + self.msg + lf_style)
    else:
      if self.author_fullname:
        try:
          out.write(self.author_fullname + lf_style)
        except UnicodeDecodeError:
         # we should use self.author_fullname instead of self.author in here, but python is a stupid language
          out.write(convert_fullname(self.author) + lf_style)

  def make_rss_item(self):
    """ Generate PyRSS2Gen Item from the commit info """
    if websvn_url == "":
      item_link = "http://www.kdevelop.org"  # if no websvn_url was provided default to this one
    else:
      item_link = websvn_url + "?rev=" + self.rev + "&amp;view=rev"
    return PyRSS2Gen.RSSItem(title = "Rev:%s [%s] - %s" % (self.rev,self.author,self.msg),
                             link = item_link,
                             author = self.author_complete,
                             description = self.msg + "<br><em>(" + self.committime + ")</em><br>" + self.filelist,
                             guid = PyRSS2Gen.Guid(item_link),
                             pubDate = self.committime)

def process_entry(e):
  rev = attr(e, "revision")
  if has_child(e, "author"):
    author = child(e, "author").textof()
  else:
    author = "anonymous"
  m = date_rx.search(child(e, "date").textof())
  msg = child(e, "msg").textof()
  msg = cgi.escape(msg)
  if m:
    tm = time.mktime(time.strptime(m.group(1), "%Y-%m-%dT%H:%M:%S"))
  else:
    die("evil date: %s" % child(e, "date").textof())
  paths = []
  file_nr = 0
  for path in child(e, "paths").children:
    if path.name != "path": die("<paths> has non-<path> child")
    nam = convert_path(path.textof())
    if nam != None:
      file_nr += 1
      if file_nr < filelist_max_files:  # only add the first "filelist_max_files" files
        if attr(path, "action") == "D":
          paths.append(nam + u" (removed)")
        elif attr(path, "action") == "A":
          paths.append(nam + u" (added)")
        else:
          paths.append(nam)
      elif file_nr == filelist_max_files: # add "(...)" if some files where omitted
        paths.append(u"(...)")
  
  if msg.startswith("SVN_SILENT"):
    return None
 
  if msg.startswith("CVS_SILENT"):
    return None
  
  if msg.startswith("This commit was manufactured by cvs2svn to create a branch"):
    return None
  
  if paths != []:
    return Entry(tm, rev, author, filelist_start_style + wrap_text(u", ".join(paths), filelist_sep_style, 65) + filelist_end_style, wrap_text(msg, filelist_sep_style, 78))

  return None

def process(fin, fout, rss_file):
  parser = qp_xml.Parser()
  root = parser.parse(fin)

  if root.name != "log": die("root is not <log>")
  
  cur = None
  item_counter = 0
  items = []

  for logentry in root.children:
    if logentry.name != "logentry": die("non <logentry> <log> child")
    e = process_entry(logentry)
    if e != None:
      if cur != None:
        if cur.can_join(e):
          cur.join(e)
        else:
          cur.dump(fout)
          item_counter += 1
          if rss_file != None and item_counter < rss_max_items:
              items.append(cur.make_rss_item())
          cur = e
      else: cur = e

  if cur != None:
    cur.dump(fout)
    if rss_file != None and item_counter > 1:
      """ Generate a PyRSS2Gen RSS2 object """
      rss = PyRSS2Gen.RSS2(   title = rss_title,
                              link = rss_link,
                              description = rss_description,
                              language = rss_language,
                              copyright = rss_copyright,
                              ttl = rss_ttl,
                              lastBuildDate = datetime.datetime.now(),
                              items = items)
      rss.write_xml(rss_file)

def usage():
  sys.stderr.write(\
"""Usage: %s [OPTIONS] [FILE]
Convert specified subversion xml logfile to GNU-style ChangeLog.

Options:
  -p, --prefix=REGEXP  set root directory of project (it will be striped off
                       from ChangeLog entries, paths outside it will be 
                       ignored)
  -x, --exclude=DIR    exclude DIR from ChangeLog (relative to prefix)
  -o, --output         set output file (defaults to 'ChangeLog')
  -s, --rss=FILE       generates a RSS 2.0 file containing commit information.
                       The item title is the Revision number and the item
                       description contains the author, date, log messages and
                       changed paths
  -d, --domain=DOMAIN  set default domain for logins not listed in users file
  -a, --authors-only   only output a list of authors
  -u, --users=FILE     read logins from specified file
  -F, --list-format    format commit logs with enumerated change list (items
                       prefixed by '- ')
  -r, --relocate=X=Y   before doing any other operations on paths, replace
                       X with Y (useful for directory moves)
  -w, --websvn=URL     when defined it will output an HTML formated file with links
                       to the commit diffsets
  -D, --delta=SECS     when log entries differ by less then SECS seconds and
                       have the same author -- they are merged, it defaults
                       to 180 seconds
  -h, --help           print this information

Users file is used to map svn logins to real names to appear in ChangeLog.
If login is not found in users file "login <login@domain>" is used.

Example users file:
john    John X. Foo <jfoo@example.org>
mark    Marcus Blah <mb@example.org>

Typical usage of this script is something like this:

  svn log -v --xml | %s -p '/foo/(branches/[^/]+|trunk)' -u aux/users
  
Please send bug reports and comments to author:
  Michal Moskal <malekith@pld-linux.org>

""" % (sys.argv[0], sys.argv[0]))

def utf_open(name, mode):
  return codecs.open(name, mode, encoding="utf-8", errors="strict")

def process_opts():
  try:
    opts, args = getopt.gnu_getopt(sys.argv[1:], "o:u:p:x:d:r:w:s:d:D:Fh:a", 
                                   ["users=", "prefix=", "domain=", "delta=",
                                    "exclude=", "help", "output=", "relocate=",
                                    "websvn=","rss=","list-format","authors-only"])
  except getopt.GetoptError:
    usage()
    sys.exit(2)
  fin = sys.stdin
  fout = None
  rss_file = None
  global kill_prefix_rx, exclude, authors_names, authors_emails, default_domain, reloc, websvn_url, lf_style, filelist_start_style, filelist_sep_style, filelist_end_style, max_join_delta, list_format, email_start_style, email_sep_style, email_end_style, output_encoding, authors_only
  for o, a in opts:
    if o in ("--prefix", "-p"):
      kill_prefix_rx = re.compile("^" + a)
    elif o in ("--exclude", "-x"):
      exclude.append(a)
    elif o in ("--help", "-h"):
      usage()
      sys.exit(0)
    elif o in ("--output", "-o"):
      fout = codecs.open(a, "w", encoding=output_encoding, errors="strict")
    elif o in ("--domain", "-d"):
      default_domain = a
    elif o in ("--authors-only", "-a"):
      authors_only = True
    elif o in ("--users", "-u"):
      f = utf_open(a, "r")
      for line in f:
        w = line.split()
        if len(line) < 1 or line[0] == '#' or len(w) < 2: 
          continue
        authors_names[w[0]] = u" ".join(w[1:len(w)-1])
        authors_emails[w[0]] = w[len(w)-1]
    elif o in ("--relocate", "-r"):
      (src, target) = a.split("=")
      reloc[src] = target
    elif o in ("--websvn", "-w"):
      websvn_url = a
      lf_style = u"<br>\n"
      filelist_start_style = u""
      filelist_sep_style = u""
      filelist_end_style = u""
      email_start_style = u"&lt;"
      email_sep_style = u" "
      email_end_style = u"&gt;"
    elif o in ("--rss", "-s"):
      rss_file = codecs.open(a, "w", encoding=output_encoding, errors="strict")
    elif o in ("--delta", "-D"):
      max_join_delta = int(a)
    elif o in ("--list-format", "-F"):
      list_format = True
    else:
      usage()
      sys.exit(2)
  if len(args) > 1:
    usage()
    sys.exit(2)
  if len(args) == 1:
    fin = open(args[0], "r")
  if fout == None:
    fout = codecs.open("ChangeLog", "w", encoding=output_encoding, errors="strict")
  process(fin, fout, rss_file)

if __name__ == "__main__":
  os.environ['TZ'] = 'UTC'
  try:
    time.tzset()
  except AttributeError:
    pass
  process_opts()
