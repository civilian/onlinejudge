;;; File: emacs-format-file
;;; Stan Warford, Oscar Chamat
;;; 17 May 2006, 01 May 2014

;(add-to-list 'load-path (expand-file-name "~/elisp"))
;(load-file "elispformat.el")
;(load-file "cl-extra.el")
;(load-file "lisp/emacs-lisp/lisp.el")
;(require 'quack)

(defun default2 ()
   "Format the whole buffer."
    (indent-sexp (point-max))
    ;(lisp-indent-function)
    ;(indent-region 0 (buffer-size)  nil)
    ;(cl-prettyprint (current-buffer))
   ;(untabify (point-min) (point-max))
   (save-buffer)
)
;;
    ;(put 'lambda 'lisp-indent-function 'defun)
    ;(put 'while 'lisp-indent-function 1)
    ;(put 'if 'lisp-indent-function 2)
   ;(c-set-style "gnu"); "stroustrup"
    ;(put 'lambda 'lisp-indent-function 'defun)
    ;(put 'while 'lisp-indent-function 1)
    ;(put 'if 'lisp-indent-function 2)
    ;(indent-region  (point-min) (point-max))
    ;(elisp-format-region (point-min) (point-max))
    ;(elisp-format-buffer)
   ;(sgml-pretty-print  (point-min) (point-max))
    ;(pp-eval-expression (point-min) (point-max) )
;;;;

;/tg/domjudge-3.4.2/output/judging/lucy/c/../indent-racket.rkt
(defun default ()
   (interactive)
    (save-buffer)
    (setq sh-indent-command (concat
                            "racket ../indent-racket.rkt "
                             )
          )
    ;(erase-buffer)
    ;(insert (buffer-name))
    (mark-whole-buffer)
    (universal-argument)
    (shell-command-on-region
     (point-min)
     (point-max)
     sh-indent-command
     (buffer-name)
     )
    (save-buffer)
)

(defun default1 ()
;    (indent-region (point-min) (point-max))
;    (untabify (point-min) (point-max))
    (put 'while 'lisp-indent-function 1)
    (indent-region (point-min) (point-max))
    (save-buffer)
)