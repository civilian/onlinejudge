ifndef TOPDIR
TOPDIR=..
endif
include $(TOPDIR)/Makefile.global

install-judgehost:
	$(INSTALL_DATA) -t $(DESTDIR)$(judgehost_libindentationdir) *.py *.rkt *.el
	chmod 755 $(DESTDIR)$(judgehost_libindentationdir)/indent-racket.rkt
