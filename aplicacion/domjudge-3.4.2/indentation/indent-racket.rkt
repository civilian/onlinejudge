;; @author: Oscar Chamat 2014
;; @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
#lang racket
;(define in (open-input-file (symbol->string (read) )))
;(define data-file (symbol->string (read) ))
;(define in (open-input-file data-file))
;(define out (open-output-file data-file))
;(define siz (file-size data-file) )

(define (read_line) 
  (let ((line (read-line)))
             (unless (eof-object? line)
               (pretty-print line)
               (read-next-line-iter )
               ) ))
               
(define (read_symbol) 
  (let ((line (read)))
             (unless (eof-object? line)
               (pretty-print line)
               (read-next-line-iter ))) )
               
(define (delete_space)
        (read-char) (read-next-line-iter)) 

(define (check_line)
    (let ((line (read-line) ))
        (unless (eof-object? line)
            (if (eq?  (peek-char) #\newline )
                (and (pretty-print line) (read-next-line-iter))
                (read-next-line-iter)
            ))))

  ;;(eq? (peek-char) #\newline)
(define (read-next-line-iter)
           (if (eq? (peek-char) #\space )
            (delete_space)
            (if (eq? (peek-char) #\newline )
                (check_line)
                (if (or (eq? (peek-char) #\() (eq? (peek-char) #\[) (eq? (peek-char) #\{) )
                        (read_symbol)
                        (read_line)
                        ))))


;(write (pretty-print  (read-bytes siz in) ) (current-output-port))
;(call-with-input-file data-file read-next-line-iter)
(read-next-line-iter)
