#!/bin/sh

# .
#
# @package   local_online_uv_judge
# @author    Oscar Chamat
# @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later


FUNCTION="$1" ; shift
SOURCE="$1" ; shift
DEST="$1"

f=$(<"$SOURCE")
#echo "$MAINCLASS"
#exit 1
# Byte-compile:
#EXITCODE=$?
#[ "$EXITCODE" -ne 0 ] && exit $EXITCODE

cat > $DEST <<EOF
#lang racket 
(pretty-print '$f')

EOF

exec racket $DEST

exit 0
