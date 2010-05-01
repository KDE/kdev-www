# Create a static version of the FAQ from the dynamic wiki FAQ page
#
# Usage:
#   wget -q --output-document=wiki_faq.html --user-agent="Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.0 (like Gecko)" http://www.kdevelop.org/mediawiki/index.php?title=FAQ&printable=yes
#   fg        # to make sure the transaction finishes before we start awk
#   awk -f bin/wiki_to_staticFAQ.awk < wiki_faq.html > 3.5/faq.html
#   rm wiki_faq.html
#
# LGPL, 2006-2008 Amilcar Lucas
{
  sub(/<script type="text\/javascript">.*<\/script>/, "")          # remove the javascript stuff (only first occurence per line)
  $0 = gensub(/<span class="mw-headline">(.*)<\/span>/, "\\1" , 1) # remove the class="mw-headline" span
  gsub(/ class='external text'/, "")                               # no need for this class (all occurences)
  $0 = gensub(/id='([a-z]*)'/, "id=\"\\1\"" , 1)                   # replace ' with "
  $0 = gensub(/id='([a-z]*)'/, "id=\"\\1\"" , 1)                   # replace ' with "
  $0 = gensub(/class='(.*?)'/, "class=\"\\1\"" , 1)                # replace ' with "
  sub(/<span class="editsection">.*<\/span> /, "")                 # remove the edit stuff (only first occurence per line)
  gsub("<span class=\"toc([a-z]*)+\">", "")                        # remove the class="toc*" span
  gsub("</span>", "")                                              # remove the /span
  gsub(" class=\"toclevel-[0-9]\"", "")                            # remove the class="toclevel-*"
  gsub(/\/mediawiki\/index\.php\/FAQ/, "")                         # use local links (all occurences)
  gsub(/docs\.kde\.org\/development/, "docs.kde.org/kde3")         # use the stable documentation branch
  gsub(/docs\.kde\.org\/en\/HEAD/, "docs.kde.org/kde3/en")         # use the stable documentation branch
  gsub(/\/mediawiki\/i/, "mediawiki/i")                            # use local links (all occurences)
  gsub(/http:\/\/www\.kdevelop\.org\/phorum5/, "phorum5")          # use local links (all occurences)
  gsub(/http:\/\/www\.kdevelop\.org\/index/, "index")              # use local links (all occurences)
  gsub(/ class="external free"/, "")                               # no need for this class (all occurences)
  gsub(/ class="image"/, "")                                       # no need for this class (all occurences)
  gsub(/ class="external text"/, "")                               # no need for this class (all occurences)
  gsub(/ rel="nofollow"/, "")                                      # no need for this class (all occurences)
  gsub(/ \/>/, ">")                                                # convert XHTML self-contained tags into HTML (all occurences)
  print
}
