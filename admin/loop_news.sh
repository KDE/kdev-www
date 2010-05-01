#!/bin/bash
A=`find *.html`

for filed in $A
do
  php ../../../admin/fix_html_text.php < $filed > encoded
  mv encoded $filed
done

exit 0