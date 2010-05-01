<?xml version="1.0" encoding="utf-8"?>

<!--

   svn2cl.xsl - xslt stylesheet for converting svn log to a normal
                changelog

   This file is based on several implementations of this conversion
   that I was not completely happy with and some other common
   xslt constructs found on the web.

   Copyright (C) 2004 Arthur de Jong.
   Copyright (C) 2005 Amilcar do Carmo Lucas.

   Redistribution and use in source and binary forms, with or without
   modification, are permitted provided that the following conditions
   are met:
   1. Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
   2. Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in
      the documentation and/or other materials provided with the
      distribution.
   3. The name of the author may not be used to endorse or promote
      products derived from this software without specific prior
      written permission.

   THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
   IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
   WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
   ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY
   DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
   DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
   GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
   INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER
   IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
   OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN
   IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

   To generate a simple ChangeLog use:
% svn log *xml -verbose | xsltproc svn2cl.xsl - > ChangeLog

   To strip some of the pathnames of the current directory:
% svn log *xml *verbose | xsltproc *stringparam strip-prefix dir/subdir svn2cl.xsl - > ChangeLog

   The parameters can be:
 *stringparam strip-prefix trunk/KDE/kdevelop : the prefix of pathnames to strip
 *stringparam ignore-filelist true : ignore the file-list
 *stringparam add-br true : add <br> elements
 *stringparam limit-logentries 5 : limit the log entries to the first n
 *stringparam websvn-url : URL of the websvn server. If defined add html style links from revisions to a websvn server

   Replace * with - - without whitespaces, the xml spec does not allow to write
   two consecutive '-' within a comment.

-->

<!--
   TODO
   - make external lookups of author names possible
   - find a place for revision numbers
   - mark deleted files as such
   - combine paths
   - make stripping of characters nicer
-->

<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns="http://www.w3.org/1999/xhtml">

 <xsl:output
   method="text"
   encoding="iso-8859-1"
   media-type="text/plain"
   omit-xml-declaration="yes"
   standalone="yes"
   indent="no" />

 <xsl:strip-space elements="*" />

 <!-- the prefix of pathnames to strip -->
 <xsl:param name="strip-prefix" select="'/'" />

 <!-- to ignore the file-list -->
 <xsl:param name="ignore-filelist" select="'false'" />

 <!-- to add <br> elements -->
 <xsl:param name="add-br" select="'false'" />

 <!-- to add html style links to a websvn server -->
 <xsl:param name="websvn-url" select="'false'" />

 <!-- to limit the nr of files in the filelist -->
 <xsl:param name="filelist_limit" select="'10'" />

 <!-- to limit the log entries to the first n -->
 <!-- if omitted, all entries are displayed -->
 <xsl:param name="limit-logentries" select="'-1'" />

 <!-- format one entry from the log -->
 <xsl:template match="logentry">
  <!-- limit the log entries to the first n, if n was specified -->
  <xsl:if test="($limit-logentries &lt; 0) or (position() &lt;= $limit-logentries)">
  <!-- Ignore SVN_SILENT commits -->
  <xsl:variable name="logmsg" select="msg" />
  <xsl:if test="not(starts-with($logmsg,'SVN_SILENT'))">
  <!-- date -->
  <xsl:apply-templates select="date" />
  <!-- two spaces -->
  <xsl:text>  </xsl:text>
  <!-- revision -->
  <xsl:apply-templates select="@revision" />
  <!-- two spaces -->
  <xsl:text>  </xsl:text>
  <!-- author's name -->
  <xsl:apply-templates select="author" />
  <!-- one optional <br> element -->
  <xsl:if test="$add-br = 'true'">
   <xsl:text disable-output-escaping="yes">&lt;br&gt;</xsl:text>
  </xsl:if>
  <!-- two newlines -->
  <xsl:text>

</xsl:text>
  <!-- the log message -->
  <xsl:apply-templates select="msg" />
  <!-- two optional <br> elements -->
  <xsl:if test="$add-br = 'true'">
   <xsl:text disable-output-escaping="yes">&lt;br&gt;&lt;br&gt;</xsl:text>
  </xsl:if>
  <!-- another two newlines -->
  <xsl:text>

</xsl:text>
 </xsl:if>
 </xsl:if>
 </xsl:template>

 <!-- format date -->
 <xsl:template match="date">
  <xsl:variable name="date" select="normalize-space(.)" />
  <xsl:value-of select="substring($date,1,10)" />
  <xsl:text> </xsl:text>
  <xsl:value-of select="substring($date,12,5)" />
 </xsl:template>

 <!-- format revision -->
 <xsl:template match="@revision">
  <xsl:text>[r</xsl:text>
  <xsl:if test="not ($websvn-url = 'false')">
    <xsl:text>&lt;A HREF="</xsl:text>
    <xsl:value-of select="$websvn-url" />
    <xsl:text>?rev=</xsl:text>
    <xsl:value-of select="." />
    <xsl:text>&amp;amp;view=rev"&gt;</xsl:text>
  </xsl:if>
  <xsl:value-of select="." />
  <xsl:if test="not ($websvn-url = 'false')">
    <xsl:text>&lt;/A&gt;</xsl:text>
  </xsl:if>
  <xsl:text>]</xsl:text>
 </xsl:template>

 <!-- format author -->
 <xsl:template match="author">
  <xsl:value-of select="normalize-space(.)" />
 </xsl:template>

 <!-- format log message -->
 <xsl:template match="msg">
  <!-- first line is indented (other indents are done in wrap template) -->
  <xsl:if test="$add-br = 'false'">
   <xsl:text>	* </xsl:text>
  </xsl:if>
  <xsl:choose>
   <xsl:when test="$ignore-filelist = 'false'">
    <!-- get paths string -->
    <xsl:variable name="paths">
     <xsl:apply-templates select="../paths" />
    </xsl:variable>
    <!-- print the paths and message nicely wrapped -->
    <xsl:call-template name="wrap">
     <xsl:with-param name="txt" select="concat($paths,': ',normalize-space(.))" />
    </xsl:call-template>
   </xsl:when>
   <xsl:otherwise>
    <!-- print the message nicely wrapped -->
    <xsl:call-template name="wrap">
     <xsl:with-param name="txt" select="normalize-space(.)" />
    </xsl:call-template>
   </xsl:otherwise>
  </xsl:choose>
 </xsl:template>

 <!-- present paths nice -->
 <xsl:template match="paths">
  <xsl:for-each select="path">
   <xsl:sort select="normalize-space(.)" data-type="text" />
   <xsl:if test="position() &lt;= $filelist_limit">
   <xsl:if test="not(position()=1)">
    <xsl:text>, </xsl:text>
   </xsl:if>
   <xsl:variable name="p1" select="normalize-space(.)" />
   <xsl:variable name="p2">
    <xsl:choose>
     <xsl:when test="starts-with($p1,'/')">
      <xsl:value-of select="substring($p1,2)" />
     </xsl:when>
     <xsl:otherwise>
      <xsl:value-of select="$p1" />
     </xsl:otherwise>
    </xsl:choose>
   </xsl:variable>
   <xsl:variable name="p3">
    <xsl:choose>
     <xsl:when test="starts-with($p2,$strip-prefix)">
      <xsl:value-of select="substring($p2,1+string-length($strip-prefix))" />
     </xsl:when>
     <xsl:otherwise>
      <xsl:value-of select="$p2" />
     </xsl:otherwise>
    </xsl:choose>
   </xsl:variable>
   <xsl:variable name="p4">
    <xsl:choose>
     <xsl:when test="starts-with($p3,'/')">
      <xsl:value-of select="substring($p3,2)" />
     </xsl:when>
     <xsl:otherwise>
      <xsl:value-of select="$p3" />
     </xsl:otherwise>
    </xsl:choose>
   </xsl:variable>
   <xsl:choose>
    <xsl:when test="$p4 = ''">
     <xsl:value-of select="'.'" />
    </xsl:when>
    <xsl:otherwise>
     <xsl:value-of select="$p4" />
    </xsl:otherwise>
   </xsl:choose>
   </xsl:if>
   <xsl:if test="(position() = $filelist_limit) and not(position()=last())">
    <xsl:text>, ... </xsl:text>
   </xsl:if>
  </xsl:for-each>
 </xsl:template>

 <!-- string-wrapping template -->
 <xsl:template name="wrap">
  <xsl:param name="txt" />
  <xsl:variable name="linelen" select="67" />
  <xsl:choose>
   <xsl:when test="(string-length($txt) &lt; $linelen) or not(contains($txt,' '))">
    <!-- this is easy, nothing to do -->
    <xsl:value-of select="$txt" />
   </xsl:when>
   <xsl:otherwise>
    <!-- find the first line -->
    <xsl:variable name="tmp" select="substring($txt,1,$linelen)" />
    <xsl:variable name="line">
     <xsl:choose>
      <xsl:when test="contains($tmp,' ')">
       <xsl:call-template name="find-line">
        <xsl:with-param name="txt" select="$tmp" />
       </xsl:call-template>
      </xsl:when>
      <xsl:otherwise>
       <xsl:value-of select="substring-before($txt,' ')" />
      </xsl:otherwise>
     </xsl:choose>
    </xsl:variable>
    <!-- print line and newline -->
    <xsl:value-of select="$line" />
    <xsl:choose>
     <xsl:when test="$add-br = 'false'">
      <xsl:text>
	  </xsl:text>
     </xsl:when>
     <xsl:otherwise>
      <xsl:text>
</xsl:text>
     </xsl:otherwise>
    </xsl:choose>
    <!-- wrap the rest of the text -->
    <xsl:call-template name="wrap">
     <xsl:with-param name="txt" select="normalize-space(substring($txt,string-length($line)+1))" />
    </xsl:call-template>
   </xsl:otherwise>
  </xsl:choose>
 </xsl:template>

 <!-- template to trim line to contain space as last char -->
 <xsl:template name="find-line">
  <xsl:param name="txt" />
  <xsl:choose>
   <xsl:when test="substring($txt,string-length($txt),1) = ' '">
    <xsl:value-of select="normalize-space($txt)" />
   </xsl:when>
   <xsl:otherwise>
    <xsl:call-template name="find-line">
     <xsl:with-param name="txt" select="substring($txt,1,string-length($txt)-1)" />
    </xsl:call-template>
   </xsl:otherwise>
  </xsl:choose>
 </xsl:template>

</xsl:stylesheet>
