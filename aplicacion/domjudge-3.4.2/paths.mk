# Variables (mostly paths) set by configure.
# This file is globally included via Makefile.global.

# General package variables
PACKAGE = domjudge
VERSION = 3.4.0
DISTNAME = $(PACKAGE)-$(VERSION)

# The following line is automatically modified by snapshot/release
# scripts, do not change manually!
PUBLISHED = release

PACKAGE_NAME      = DOMjudge
PACKAGE_VERSION   = 3.4.0
PACKAGE_STRING    = DOMjudge 3.4.0
PACKAGE_TARNAME   = domjudge
PACKAGE_BUGREPORT = domjudge-devel@lists.A-Eskwadraat.nl

# Compilers and FLAGS
CC  = gcc
CXX = g++
CPP = gcc -E

CFLAGS   = -g -O1 -Wall -fstack-protector -D_FORTIFY_SOURCE=2 -fPIE -Wformat -Wformat-security -ansi -pedantic 
CXXFLAGS = -g -O1 -Wall -fstack-protector -D_FORTIFY_SOURCE=2 -fPIE -Wformat -Wformat-security -ansi -pedantic
CPPFLAGS = 
LDFLAGS  = -fPIE -pie -Wl,-z,relro -Wl,-z,now 

EXEEXT = 
OBJEXT = .o

# Other programs
LN_S    = ln -s
MKDIR_P = /usr/bin/mkdir -p
INSTALL = /usr/bin/install -c


# Build checktestdata program?
CHECKTESTDATA_ENABLED = yes

# Use Linux cgroups?
USE_CGROUPS = 0

# Submit protocols
SUBMIT_DEFAULT    = 2
SUBMIT_ENABLE_CMD = 1
SUBMIT_ENABLE_WEB = 1

# libcgroup
LIBCGROUP = 

# libmagic
LIBMAGIC = -lmagic

# libcURL
CURL_CFLAGS = 
CURL_LIBS   = -lcurl
CURL_STATIC = /usr/lib/libcurl.a -Wl,-O1,--sort-common,--as-needed,-z,relro -lssh2 -lssl -lcrypto -lssl -lcrypto -lz

# libboost
BOOST_CPPFLAGS  = -I/usr/include
BOOST_LDFLAGS   = -L/usr/lib64
BOOST_REGEX_LIB = -lboost_regex

# libgmpxx
LIBGMPXX = -lgmp -lgmpxx

# htpasswd
HTPASSWD = htpasswd

# User:group file ownership of password files
DOMJUDGE_USER   = civilian
WEBSERVER_GROUP = www-data

# Autoconf prefixes and paths
FHS_ENABLED    = no

prefix         = /home/civilian/repos/tg/domjudge-3.4.2
exec_prefix    = ${prefix}

bindir         = ${exec_prefix}/bin
sbindir        = ${exec_prefix}/sbin
libexecdir     = ${exec_prefix}/libexec
sysconfdir     = ${prefix}/etc
sharedstatedir = ${prefix}/com
localstatedir  = ${prefix}/var
libdir         = ${exec_prefix}/lib
includedir     = ${prefix}/include
oldincludedir  = /usr/include
datarootdir    = ${prefix}/share
datadir        = ${datarootdir}
infodir        = ${datarootdir}/info
localedir      = ${datarootdir}/locale
mandir         = ${datarootdir}/man
docdir         = ${datarootdir}/doc/${PACKAGE_TARNAME}
htmldir        = ${docdir}
dvidir         = ${docdir}
pdfdir         = ${docdir}
psdir          = ${docdir}

# Installation paths
domserver_bindir       = /home/civilian/repos/tg/domjudge-3.4.2/bin
domserver_etcdir       = /home/civilian/repos/tg/domjudge-3.4.2/etc
domserver_wwwdir       = /home/civilian/repos/tg/domjudge-3.4.2/www
domserver_sqldir       = /home/civilian/repos/tg/domjudge-3.4.2/sql
domserver_libdir       = /home/civilian/repos/tg/domjudge-3.4.2/lib
domserver_libextdir    = /home/civilian/repos/tg/domjudge-3.4.2/lib/ext
domserver_libwwwdir    = /home/civilian/repos/tg/domjudge-3.4.2/lib/www
domserver_libsubmitdir = /home/civilian/repos/tg/domjudge-3.4.2/lib/submit
domserver_logdir       = /home/civilian/repos/tg/domjudge-3.4.2/output/log
domserver_rundir       = /home/civilian/repos/tg/domjudge-3.4.2/output/run
domserver_tmpdir       = /home/civilian/repos/tg/domjudge-3.4.2/output/tmp
domserver_submitdir    = /home/civilian/repos/tg/domjudge-3.4.2/output/submissions

judgehost_bindir       = /home/civilian/repos/tg/domjudge-3.4.2/bin
judgehost_etcdir       = /home/civilian/repos/tg/domjudge-3.4.2/etc
judgehost_libdir       = /home/civilian/repos/tg/domjudge-3.4.2/lib
judgehost_libjudgedir  = /home/civilian/repos/tg/domjudge-3.4.2/lib/judge
#online judge uv
judgehost_libdocumentationdir  = /home/civilian/repos/tg/domjudge-3.4.2/lib/documentation
judgehost_libindentationdir  = /home/civilian/repos/tg/domjudge-3.4.2/lib/indentation
#online judge uv
judgehost_logdir       = /home/civilian/repos/tg/domjudge-3.4.2/output/log
judgehost_rundir       = /home/civilian/repos/tg/domjudge-3.4.2/output/run
judgehost_tmpdir       = /home/civilian/repos/tg/domjudge-3.4.2/output/tmp
judgehost_judgedir     = /home/civilian/repos/tg/domjudge-3.4.2/output/judging

domjudge_docdir        = /home/civilian/repos/tg/domjudge-3.4.2/doc

domserver_dirs = $(domserver_bindir) $(domserver_etcdir) $(domserver_wwwdir) \
                 $(domserver_libdir) $(domserver_libsubmitdir) $(domserver_libextdir) \
                 $(domserver_libwwwdir) $(domserver_logdir) $(domserver_rundir) \
                 $(domserver_tmpdir) $(domserver_submitdir) $(domserver_sqldir)/upgrade \
                 $(addprefix $(domserver_wwwdir)/images/,affiliations countries teams) \
                 $(addprefix $(domserver_wwwdir)/,public team jury plugin js)

judgehost_dirs = $(judgehost_bindir) $(judgehost_etcdir) $(judgehost_libdir) \
                 $(judgehost_libjudgedir) $(judgehost_logdir) $(judgehost_rundir) \
                 $(judgehost_tmpdir) $(judgehost_judgedir) $(judgehost_libdocumentationdir) $(judgehost_libindentationdir)

docs_dirs      = $(addprefix $(domjudge_docdir)/,admin judge team examples logos)

# Macro to substitute configure variables.
# Defined in makefile to allow for expansion of ${prefix} etc. during
# build and install, conforming to the GNU coding standards, see:
# http://www.gnu.org/software/hello/manual/autoconf/Installation-Directory-Variables.html
define substconfigvars
@[ -n "$(QUIET)" ] || echo "Substituting configure variables in '$@'."
@cat $< | sed \
	-e "s|@configure_input[@]|Generated from '$<' on `date`.|g" \
	-e 's,@DOMJUDGE_VERSION[@],3.4.0,g' \
	-e 's,@domserver_bindir[@],/home/civilian/repos/tg/domjudge-3.4.2/bin,g' \
	-e 's,@domserver_etcdir[@],/home/civilian/repos/tg/domjudge-3.4.2/etc,g' \
	-e 's,@domserver_wwwdir[@],/home/civilian/repos/tg/domjudge-3.4.2/www,g' \
	-e 's,@domserver_sqldir[@],/home/civilian/repos/tg/domjudge-3.4.2/sql,g' \
	-e 's,@domserver_libdir[@],/home/civilian/repos/tg/domjudge-3.4.2/lib,g' \
	-e 's,@domserver_libextdir[@],/home/civilian/repos/tg/domjudge-3.4.2/lib/ext,g' \
	-e 's,@domserver_libwwwdir[@],/home/civilian/repos/tg/domjudge-3.4.2/lib/www,g' \
	-e 's,@domserver_libsubmitdir[@],/home/civilian/repos/tg/domjudge-3.4.2/lib/submit,g' \
	-e 's,@domserver_logdir[@],/home/civilian/repos/tg/domjudge-3.4.2/output/log,g' \
	-e 's,@domserver_rundir[@],/home/civilian/repos/tg/domjudge-3.4.2/output/run,g' \
	-e 's,@domserver_tmpdir[@],/home/civilian/repos/tg/domjudge-3.4.2/output/tmp,g' \
	-e 's,@domserver_submitdir[@],/home/civilian/repos/tg/domjudge-3.4.2/output/submissions,g' \
	-e 's,@judgehost_bindir[@],/home/civilian/repos/tg/domjudge-3.4.2/bin,g' \
	-e 's,@judgehost_etcdir[@],/home/civilian/repos/tg/domjudge-3.4.2/etc,g' \
	-e 's,@judgehost_libdir[@],/home/civilian/repos/tg/domjudge-3.4.2/lib,g' \
	-e 's,@judgehost_libjudgedir[@],/home/civilian/repos/tg/domjudge-3.4.2/lib/judge,g' \
        -e 's,@judgehost_libdocumentationdir[@],/home/civilian/repos/tg/domjudge-3.4.2/lib/documentation,g' \
        -e 's,@judgehost_libindentationdir[@],/home/civilian/repos/tg/domjudge-3.4.2/lib/indentation,g' \
	-e 's,@judgehost_logdir[@],/home/civilian/repos/tg/domjudge-3.4.2/output/log,g' \
	-e 's,@judgehost_rundir[@],/home/civilian/repos/tg/domjudge-3.4.2/output/run,g' \
	-e 's,@judgehost_tmpdir[@],/home/civilian/repos/tg/domjudge-3.4.2/output/tmp,g' \
	-e 's,@judgehost_judgedir[@],/home/civilian/repos/tg/domjudge-3.4.2/output/judging,g' \
	-e 's,@domjudge_docdir[@],/home/civilian/repos/tg/domjudge-3.4.2/doc,g' \
	-e 's,@DOMJUDGE_USER[@],civilian,g' \
	-e 's,@WEBSERVER_GROUP[@],www-data,g' \
	-e 's,@BEEP[@],@BEEP@,g' \
	-e 's,@RUNUSER[@],domjudge-run,g' \
	-e 's,@USE_CGROUPS[@],0,g' \
	-e 's,@SUBMIT_DEFAULT[@],2,g' \
	-e 's,@SUBMIT_ENABLE_CMD[@],1,g' \
	-e 's,@SUBMIT_ENABLE_WEB[@],1,g' \
	> $@
endef
