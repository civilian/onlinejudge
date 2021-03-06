// Generated from 'submit-config.h.in' on Tue May 20 23:04:08 COT 2014.

#ifndef _SUBMIT_CONFIG_
#define _SUBMIT_CONFIG_

/* Define the command to copy files to the user submit directory in the
   submit client differently under Windows and Linux */
#if defined(__CYGWIN__) || defined(__CYGWIN32__)
#define COPY_CMD "c:/cygwin/bin/cp"
#else
#define COPY_CMD "cp"
#endif

/* Submission methods and default. */
#define SUBMIT_DEFAULT    2
#define SUBMIT_ENABLE_CMD 0
#define SUBMIT_ENABLE_WEB 1

/* Default TCP port to use for command-line submit client/daemon. */
#define SUBMITPORT   9147

/* Team HOME subdirectory to use for storing temporary/log/etc. files
   and permissions. */
#define USERDIR      ".domjudge"
#define USERPERMDIR  0700
#define USERPERMFILE 0600

/* Last modified time in minutes after which to warn for submitting
   an old file. */
#define WARN_MTIME   5

/* List of auto-detected language extensions by the submit client.
   Format: 'LANG,MAINEXT[,EXT]... [LANG...]' where:
   - LANG is the language name displayed,
   - MAINEXT is the extension corresponding to the langid in DOMjudge,
   - EXT... are comma separated additional detected language extensions.
   This list only needs to be modified when additional languages are
   added and should be kept in sync with the list in domserver-config.php.
*/
#define LANG_EXTS    "C,c C++,cpp,cc,c++ Java,java Pascal,pas,p Haskell,hs,lhs Perl,pl POSIX-shell,sh C#,csharp,cs AWK,awk Python2,py2,py Python3,py3 Bash,bash Ada,adb,ads Fortran,f95,f90 Scala,scala Lua,lua"

#endif /* _SUBMIT_CONFIG_ */
