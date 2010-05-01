# This script can be used to extract a section out of the ChangeLog files at www.kdevelop.org
# The section starts at the last released version and ends at the "endversion" command line parameter
# The resulting section should then be commited to CVS
#
# An example usage for the KDevelop 3.3.1 release:
#    Get the http://www.kdevelop.org/index.html?filename=3.3/ChangeLog.html file and save it as "ChangeLog.html"
#    awk -v endversion=500184 -f extract_ChangeLog_section.awk < ChangeLog.html > ../3.3/ChangeLog_3_3_1.ihtml
#    commit the "../3.3/ChangeLog_3_3_1.ihtml" to cvs
#
#  (C) 2006-01-31 Amilcar Lucas
#
BEGIN { print "\n"; p = 0 }
/<h3><a name="/            { p = 0 }  # end of interesting (real content) text
$0 ~ endversion            { p = 1 }  # begin of interesting (real content) text
{ if (p > 0) {  print }
}
END { print "<br>"}
