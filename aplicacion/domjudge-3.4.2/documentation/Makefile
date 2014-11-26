ifndef TOPDIR
TOPDIR=..
endif
include $(TOPDIR)/Makefile.global

install-judgehost:
	$(INSTALL_DATA) -t $(DESTDIR)$(judgehost_libdocumentationdir)    *.java *.jar
	javac $(DESTDIR)$(judgehost_libdocumentationdir)/*.java
	chmod 755 $(DESTDIR)$(judgehost_libdocumentationdir)/*.class
