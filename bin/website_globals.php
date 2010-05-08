<?php
# begin 16 April 2004
# (c) 2004-2007 Amilcar Lucas <amilcar@kdevelop.org>
# published under the GNU GPL

global $langcodes;
global $basedir;
global $lsv;
global $HEAD_user_manual_url;
global $K_VERSION;
global $KPLATFORM_VERSION;
global $stable_KDE_BRANCH;
global $stable_KDevelop_BRANCH;

// START - EDIT THESE FOR MAJOR RELEASES - START
$lsv='4.0'; // Latest Stable Version written in major version (x.x)

// These only exist for KDevelop >= 3.0 (but are mostly used for KDevelop >= 3.2)
$K_VERSION['3.0'] = array(
     'base_version'             =>'3.0',
     'series_version'           =>'3.0.x',
     'latest_version'           =>'3.0.4',
     'kde_base_version'         =>'3.2',
     'branch'                   =>'KDE/3.2',
     'branch_path'              =>'branches/KDE/3.2/kdevelop',
     'i18n_stats_url'           =>'stable',
     'release_date'             => strtotime('02/03/2004'),
     'kde_release'              => '3.2',
     'kde_release_schedule'     => 'http://techbase.kde.org/Schedules/KDE_3.2_Release_Schedule',
     'min_required_kde_version' =>'3.0');

$K_VERSION['3.1'] = array(
     'base_version'             =>'3.1',
     'series_version'           =>'3.1.x',
     'latest_version'           =>'3.1.2',
     'kde_base_version'         =>'3.3',
     'branch'                   =>'KDE/3.3',
     'branch_path'              =>'branches/KDE/3.3/kdevelop',
     'i18n_stats_url'           =>'stable',
     'release_date'             => strtotime('08/19/2004'),
     'kde_release'              => '3.3',
     'kde_release_schedule'     => 'http://techbase.kde.org/Schedules/KDE_3.3_Release_Schedule',
     'min_required_kde_version' =>'3.2');

$K_VERSION['3.2'] = array(
     'base_version'             =>'3.2',
     'series_version'           =>'3.2.x',
     'latest_version'           =>'3.2.3',
     'kde_base_version'         =>'3.4',
     'branch'                   =>'KDE/3.4',
     'branch_path'              =>'branches/KDE/3.4/kdevelop',
     'i18n_stats_url'           =>'stable',
     'release_date'             => strtotime('03/16/2005'),
     'kde_release'              => '3.4',
     'kde_release_schedule'     => 'http://techbase.kde.org/Schedules/KDE_3.4_Release_Schedule',
     'min_required_kde_version' =>'3.2');

$K_VERSION['3.3'] = array(
     'base_version'             =>'3.3',
     'series_version'           =>'3.3.x',
     'latest_version'           =>'3.3.6',
     'kde_base_version'         =>'3.5',
     'branch'                   =>'kdevelop/3.3',
     'branch_path'              =>'branches/kdevelop/3.3',
     'i18n_stats_url'           =>'stable',
     'release_date'             => strtotime('11/29/2005'),
     'kde_release'              => '3.5',
     'kde_release_schedule'     => 'http://techbase.kde.org/Schedules/KDE_3.5_Release_Schedule',
     'min_required_kde_version' =>'3.2');

$K_VERSION['3.4'] = array(
     'base_version'             =>'3.4',
     'series_version'           =>'3.4.x',
     'latest_version'           =>'3.4.1',
     'kde_base_version'         =>'3.5',
     'branch'                   =>'KDE/3.5',
     'branch_path'              =>'branches/KDE/3.5/kdevelop',
     'i18n_stats_url'           =>'stable',
     'release_date'             => strtotime('01/25/2007'),
     'kde_release'              => '3.5.6',
     'kde_release_schedule'     => 'http://techbase.kde.org/Schedules/KDE_3.5_Release_Schedule',
     'min_required_kde_version' =>'3.4');

$K_VERSION['3.5'] = array(
     'base_version'             =>'3.5',
     'series_version'           =>'3.5.x',
     'latest_version'           =>'3.5.5',
     'kde_base_version'         =>'3.5',
     'branch'                   =>'KDE/3.5',
     'branch_path'              =>'branches/KDE/3.5/kdevelop',
     'i18n_stats_url'           =>'stable',
     'release_date'             => strtotime('10/16/2007'),
     'kde_release'              => '3.5.8',
     'kde_release_schedule'     => 'http://techbase.kde.org/Schedules/KDE_3.5_Release_Schedule',
     'min_required_kde_version' =>'3.4');

$K_VERSION['4.0'] = array(
     'base_version'             =>'4.0',
     'series_version'           =>'4.0.x',
     'latest_version'           =>'4.0.0',
     'latest_release_date'      =>'2010-05-01', // tag date
     'kde_base_version'         =>'4.4',
     'branch'                   =>'extragear-kde4/sdk/kdevelop/4.0',
     'branch_path'              =>'branches/stable/extragear-kde4/sdk/kdevelop/4.0',
     'i18n_stats_url'           =>'stable',
     'release_date'             => strtotime('05/01/2010'), // mm/dd/yyyy
     'kde_release'              => '4.4',
     'kde_release_schedule'     => 'http://www.kdevelop.org/mediawiki/index.php/KDevelop_4/4.0_Release_Schedule',
     'min_required_kde_version' =>'4.3');

$KPLATFORM_VERSION['1.0'] = $K_VERSION['4.0'];
$KPLATFORM_VERSION['1.0']['base_version']   = '1.0';
$KPLATFORM_VERSION['1.0']['series_version'] = '1.0.x';
$KPLATFORM_VERSION['1.0']['latest_version'] = '1.0.0';
$KPLATFORM_VERSION['1.0']['branch_path']    = 'branches/stable/extragear-kde4/sdk/kdevplatform/1.0';

$K_VERSION['HEA'] = array(
     'base_version'             =>'HEAD',
     'series_version'           =>'HEAD',
     'latest_version'           =>'HEAD',
     'latest_release_date'      =>'2011-01-31',  // tag date
     'kde_base_version'         =>'HEAD',
     'branch'                   =>'trunk/extragear/sdk/',  // trunk/KDE
     'branch_path'              =>'trunk/extragear/sdk/kdevelop',
     'i18n_stats_url'           =>'trunk',
     'release_date'             => strtotime('01/31/2011'), // mm/dd/yyyy
     'kde_release'              => '',
     'kde_release_schedule'     => 'http://techbase.kde.org/Schedules/KDE4/4.5_Release_Schedule',
     'min_required_kde_version' =>'4.3');

// For the user manuals and stuff
$stable_KDE_BRANCH = $K_VERSION[$lsv]['branch'];
$stable_KDevelop_BRANCH = $K_VERSION[$lsv]['branch_path'];
$stable_KDE_major_version = $K_VERSION[$lsv]['kde_base_version'];
$stable_lxr_url = "http://lxr.kde.org/source/kdevelop/?v=$stable_KDE_major_version-branch";
$stable_user_manual_url = "http://docs.kde.org/kde3/en/kdevelop/kdevelop/";

// END - EDIT THESE FOR MAJOR RELEASES - END


// Active languages on the main Page
$activelanguages = array('en','ar','cs','de','es','fr','hu','id','it','nl','pl','pt','pt_BR','ro','ru','tr','uk','zh_CN');

// Languages used in the 'web_translation_status.html' page
$langcodes = array('ar','cs','de','es','fr','hu','id','it','nl','pl','pt','pt_BR','ro','ru','tr','uk','zh_CN');

// This will hopefully work in all situations (apache served or command line invoked)
// It allows the entire website to adapt itself to it's location on the filesystem
// The dirname is invoked twice to remove the last subdir (/bin) of the path
$basedir = dirname(dirname(__FILE__)) . '/';

// If a user (ip) retries to access the site after this timeout,
// and he has not been trapped in the "bad robots trap" it (ip) gets cleaned.
$timeout_minutes = 8;

// How many 404 + 400 hits are required to forbid (403) a user (ip)
$bad_hits_treshold = 4;

// How many times needs an user ip to get trapped to make him get trapped permanently
$trapped_treshold = 2;

?>
