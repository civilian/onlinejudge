;;;
;;; <primitiva>                     ::= +|-|*|/|%|&
;;; <bool-primitiva>            ::= <|>|<=|>=|is 
;;; <bool-oper>                   ::= not|and|or


; Comments bla bal
; 

(define functi
   (lambda (jogador carta)
     (append (list (car jogador) (add1 (cadr jogador)) carta) (cddr jogador))
     (if (figura-carta?-sol carta)
       (let ((figura (valor-carta-sol carta)))
         (cond
          ((string=? figura "as") 13)
          ((string=? figura "rei") 12)
          ((string=? figura "valete") 11)
          ((#t 10))))
       (sub1 (valor-carta-sol carta)))))


(define functi2 (lambda (var1 var2) (lambda (foo) "foo" "bar")))


(define functiWhile (lambda (var1 var2) (while (foo) "foo" "bar")))


(define functiIf (lambda (var1 var2) (if (foo) "foo" "bar")))


;(define in (open-input-file (symbol->string (read) )))
;(define data-file (symbol->string (read) ))
;(define in (open-input-file data-file))
;(define out (open-output-file data-file))
;(define siz (file-size data-file) )

(define (read-next-line-iter)
   (let ((line (read)))
     (unless (eof-object? line)
       (pretty-print line)
       (newline)
       (read-next-line-iter))))



;(write (pretty-print  (read-bytes siz in) ) (current-output-port))
;(call-with-input-file data-file read-next-line-iter)
(read-next-line-iter)


(define naipe-jogador-sol
   (lambda (jogador naipe)
     (letrec ((aux
               (lambda (cartas naipe num)
                 (if (null? cartas)
                   num
                   (if (equal? naipe (naipe-carta-sol (car cartas)))
                     (aux (cdr cartas) naipe (add1 num))
                     (aux (cdr cartas) naipe num))))))
       (aux (cddr jogador) naipe 0))))

  
(define (read-next-line-iter)
   (if (or (eq? (peek-char) #\() (eq? (peek-char) #\[) (eq? (peek-char) #\{))
     (read_symbol)
     (read_line)))
