
#!/bin/sh
#
# /etc/init.d/judged.php
# Subsystem file for "judged.php" server
#
# chkconfig: 2345 95 05 (1)
# description: judged.php server daemon
#
# processname: judged.php
# config: /etc/judged/ mySystem.conf NOT NECESARY NOTHING THERE
# config: /etc/sysconfig/mySystem NOT NECESARY NOTHING THERE
# pidfile: /var/run/MySystem.pid NOT NECESARY NOTHING THERE

# source function library
#. /etc/rc.d/init.d/functions NOT NECESARY NOTHING THERE

# pull in sysconfig settings
#[ -f /etc/sysconfig/mySystem ] && . /etc/sysconfig/mySystem NOT NECESARY NOTHING THERE

#TODO: this is temporal the dir is supoused to be configurabled 
#so the desing has to change to make the autorun easy
MOODLE_DIR="/home/invitado/public_html/moodle"

PIDID=`pgrep -f "judged.php"`
RUN_CMD="php $MOODLE_DIR/local/onlinejudge/cli/judged.php"
RETVAL=0
prog="judged"

start() {
        if ! [ -z ${PIDID} ]; then
            echo "$prog is already running (pid $PIDID)"
        else
            echo -n $"Starting $prog:"
            ${RUN_CMD} >/dev/null 2>&1
        fi
        RETVAL=$?
        [ "$RETVAL" = 0 ] && touch /var/lock/$prog
        echo
}

stop() {
        echo -n $"Stopping $prog:"
        kill ${PIDID}
        RETVAL=$?
        [ "$RETVAL" = 0 ] && rm -f /var/lock/$prog
        echo
}


case "$1" in
        start)
                start
                ;;
        stop)
                stop
                ;;
        restart)
                stop
                start
                ;;
        condrestart)
                if [ -f /var/lock/$prog ] ; then
                        stop
                        # avoid race
                        sleep 3
                        start
                fi
                ;;
        *)
                echo $"Usage: $0 {start|stop|restart|condrestart}"
                RETVAL=1
esac
exit $RETVAL