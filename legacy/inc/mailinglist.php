<?php
module_head($l_online_un_subscribe);

// This is the php4 way to do things
if (isset($_POST['action']) and ($_POST['action']==$l_subscribe or $_POST['action']==$l_unsubscribe))
  $action = $_POST['action'];
// for security reasons, the e-mail length is truncated
if (isset($_POST['email']) and strlen($_POST['email'])<256)
  $email = addslashes(trim($_POST['email']));
if (isset($_POST['maillist']))
  if ($_POST['maillist']=='kdevelop-request@kdevelop.org' or
      $_POST['maillist']=='kdevelop-devel-request@kdevelop.org' or
      $_POST['maillist']=='kdevelop-bugs-request@kdevelop.org')
    $maillist = $_POST['maillist'];

if (isset($action) && isset($email) && isset($maillist) && ($email !=$l_default_email)) {
  if ($action==$l_subscribe) {
    mail($maillist,"subscribe $email","subscribe $email","From: $email
Content-Type: text/plain; charset=iso-8859-1
Content-Transfer-Encoding: 8bit");
  } else {
    mail($maillist,"","unsubscribe $email","From: $email
Content-Type: text/plain; charset=iso-8859-1
Content-Transfer-Encoding: 8bit");
  }
  echo "<center>$action $email $maillist - $l_done</center>";
} else {
  echo '<form method="POST" action="index.html?filename=mailinglist.html">
  <table>
    <tr>
      <td style="border: 0pt none;"><b>';
  echo "$l_Mailinglists</b></td>
      <td style=\"border: 0pt none;\"><select name=\"maillist\">
        <option value=\"kdevelop-request@kdevelop.org\" SELECTED>$l_kdevelop_users
        <option value=\"kdevelop-devel-request@kdevelop.org\">$l_kdevelop_devel
        <option value=\"kdevelop-bugs-request@kdevelop.org\">$l_kdevelop_bugs
      </select></td>
      <td style=\"border: 0pt none;\">Email:&nbsp;&nbsp;<input type=text name=\"email\" size=\"40\" value=\"$l_default_email\"></td>
    </tr>";
  echo "    <tr>
      <td style=\"border: 0pt none;\"></td>
      <td style=\"border: 0pt none;\"><input type=\"submit\" name=\"action\" value=\"$l_subscribe\"></td>
      <td style=\"border: 0pt none;\"><input type=\"submit\" name=\"action\" value=\"$l_unsubscribe\"></td>
    </tr>
  </table>
</form>
";
}
module_tail();
?>