;;; File: cpp.el
;;; Stan Warford, Oscar Chamat
;;; 17 May 2006, 01 May 2014

;(require 'sgml-mode)

(defun default ()
   (interactive)
    (save-buffer)
    (setq sh-indent-command (concat
                             "indent -kr -ts8 --no-tabs"
                             buffer-file-name
                             )
          )
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

(defun gnu ()
   (interactive)
    (save-buffer)
    (setq sh-indent-command (concat
                             "indent -gnu -ts8 --no-tabs"
                             buffer-file-name
                             )
          )
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

(defun bsd ()
   (interactive)
    (save-buffer)
    (setq sh-indent-command (concat
                             "indent -orig -ts8 --no-tabs"
                             buffer-file-name
                             )
          )
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

(defun linux ()
   (interactive)
    (save-buffer)
    (setq sh-indent-command (concat
                             "indent -linux -ts8 --no-tabs"
                             buffer-file-name
                             )
          )
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

(defun kr ()
   (interactive)
    (save-buffer)
    (setq sh-indent-command (concat
                             "indent -kr -ts8 --no-tabs"
                             buffer-file-name
                             )
          )
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

;; Not in use

(defun java ()
   "Format the whole buffer."
   (c-set-style "java"); "stroustrup"
   (indent-region (point-min) (point-max) nil)
   (untabify (point-min) (point-max))
   (save-buffer)
)

(defun c-reformat-buffer()
    (interactive)
    (save-buffer)
    (setq sh-indent-command (concat
                             "indent -st -bad --blank-lines-after-procedures "
                             "-bli0 -i4 -l79 -ncs -npcs -nut -npsl -fca "
                             "-lc79 -fc1 -cli4 -bap -sob -ci4 -nlp "
                             buffer-file-name
                             )
          )
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

;emacs-format-file
(defun default_diff ()
   "Format the whole buffer."
   (c-set-style "gnu"); whitesmith bsd ellemtel java whitesmith stroustrup linux k&r
   (indent-region (point-min) (point-max) nil)
   ;(sgml-pretty-print  (point-min) (point-max))
   (untabify (point-min) (point-max))
   (save-buffer)
)

(defun diff ()
   "Format the whole buffer."
   (c-set-style "whitesmith")
	(setq c-basic-offset 8)
   (indent-region (point-min) (point-max) nil)
	(sgml-pretty-print  (point-min) (point-max))
   (untabify (point-min) (point-max))
   (save-buffer)
)
