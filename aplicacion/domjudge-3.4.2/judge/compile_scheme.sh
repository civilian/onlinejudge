#!/bin/sh

# scheme compile wrapper-script for 'compile.sh'.
# See that script for syntax and more info.
#
# This script byte-compiles with raco compiler and
# generates a shell script to run it with the racket interpreter later.
# It uses the main.rkt file
#
# NOTICE: this compiler script cannot be used with the USE_CHROOT
# configuration option turned on, unless proper preconfiguration of
# the chroot environment has been taken care of!
# @package   local_online_uv_judge
# @author    Oscar Chamat
# @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later


DEST="$1" ; shift
MEMLIMIT="$1" ; shift
MAINSOURCE="$1"

#echo "$MAINCLASS"
#exit 1
# Byte-compile:
#EXITCODE=$?
#[ "$EXITCODE" -ne 0 ] && exit $EXITCODE
chmod a+x "$@"
raco make "$@"

# Look for class  'main':
for cn in $(find * -type f -regex '^main\..*$' ); do
        if [ -n "$MAINCLASS" ]; then
                echo "Warning: found another 'main' in '$cn'"
        else
                echo "Info: using 'main archive' as '$cn'"
                MAINCLASS=$cn
        fi
done
if [ -z "$MAINCLASS" ]; then
	echo "Error: no 'main archive' found."
	exit 1
fi

# Write executing script:
# Executes racket byte-code interpreter
# this means cat(sent) to destiny until eof (end of file)||
cat > $DEST <<EOF
#!/bin/sh
# Generated shell-script to execute racket interpreter on source.
# Detect dirname and change dir to prevent class not found errors.
if [ "\${0%/*}" != "\$0" ]; then
	cd "\${0%/*}"
fi

exec racket $MAINCLASS
EOF

chmod a+x $DEST

exit 0
