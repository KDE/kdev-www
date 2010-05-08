<?php
global $l_was_released_with, $l_bugfix_versions, $l_scheduled_for_versions, $l_scheduled_versions, $l_release_schedule, $l_unstable_development, $l_stable_version, $l_entirely_new_code;

$k_rev= array_reverse($K_VERSION);
foreach($k_rev as $v) {
  if ($v['base_version'] == '4.0') {
  echo '  <tr>
    <td><a href="http://websvn.kde.org/'.$v['branch_path'].'">'.$v['branch'].'</a></td>';
    printf("    <td>$l_stable_version", 'KDE&nbsp;&gt;=&nbsp;'.$v['min_required_kde_version']);
    if ($v['base_version'] == '3.0' || $v['base_version'] == '4.0')
      echo "<br>$l_entirely_new_code";
    echo '</td>';
    echo '    <td><a href="index.html?filename='.$v['base_version'].'/announce-kdevelop-'.$v['base_version'].'.html">KDevelop-'.$v['base_version'].".0</a> $l_was_released_with";
    printf("$l_scheduled_versions </td>",
      '<a href="index.html?filename='.$v['base_version'].'/kdevelop.html">KDevelop-'.$v['series_version'].'</a>');
    echo '<td>';
    if ($v['kde_release'])
      printf("$l_release_schedule</a><br>",
      '<a href="'.$v['kde_release_schedule'].'">KDevelop-'.$v['series_version']);
    echo '( '.strftime("%d %B %Y", $v['release_date']).' )</td>';
    echo '  </tr>';
    continue;
  }
  echo '  <tr>
    <td><a href="http://websvn.kde.org/'.$v['branch_path'].'">'.$v['branch'].'</a></td>';
  if ($v['base_version'] == 'HEAD') {
    printf("    <td>$l_unstable_development", 'KDE&nbsp;&gt;=&nbsp;'.$v['min_required_kde_version']);
    echo '    <td><a href="/mediawiki/index.php/KDevelop_4">KDevelop-4.0</a></td>';
  } else {
    printf("    <td>$l_stable_version", 'KDE&nbsp;&gt;=&nbsp;'.$v['min_required_kde_version']);
    if ($v['base_version'] == '3.0' || $v['base_version'] == '4.0')
      echo "<br>$l_entirely_new_code";
    echo '</td>';
    echo '    <td><a href="index.html?filename='.$v['base_version'].'/announce-kdevelop-'.$v['base_version'].'.html">KDevelop-'.$v['base_version'].".0</a> $l_was_released_with <a href=\"http://www.kde.org/announcements/announce-".$v['kde_release'].'.php">KDE-'.$v['kde_release'].'</a><br>';
    printf("$l_scheduled_versions </td>",
      '<a href="index.html?filename='.$v['base_version'].'/kdevelop.html">KDevelop-'.$v['series_version'].'</a>',
      '<a href="index.html?filename=versions.html">'.$l_bugfix_versions.'</a>',
      'KDE-'.$v['kde_base_version'].'.x');
  }
  echo '<td>';
  if ($v['kde_release'])
    printf("$l_release_schedule</a><br>",
    '<a href="'.$v['kde_release_schedule'].'">KDE-'.$v['kde_release']);
  echo '( '.strftime("%d %B %Y", $v['release_date']).' )</td>';
  echo '  </tr>';
}
?>
