
import java.io.IOException;
import java.util.Arrays;

/**
 * @version 0.1
 * @author Oscar Chamat
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/*!
 * Script que califica en porcentajes dado como numeros entre 0 y 1 el promedio
 * de cumplimiento de ciertas etiquetas de documentación en los diferentes
 * archivos
 */
public class DocumentationCheck {

    /**
     * main
     *
     * @param args parametros de configuracion
     */
    public static void main(String[] args) throws Exception {
        //pruebas/soloComentario pruebas/CPPFileNavigator.java pruebas/D.java pruebas/Tree.java pruebas/Node.java
        if (args.length == 0) {
            System.out
                    .println("Uso: java DocumentationCheck [options] -files [archivos]");
        }
        DocumentationCheck obj = new DocumentationCheck(args);
        double fin = obj.checkDocumentation();
        // D.dbg("final", fin);
        System.out.println(fin);
    }
    int posible, positive;// las posibles etiquetas que deberían ir y la
    // cantidad que si van
    String[] args;
    String lang, param, ans;// ans es return.
    boolean author, version, copyright, license, pack;// pack es package

    /**
     * Constructor de la clase
     *
     * @param args parametros de configuracion
     */
    public DocumentationCheck(String[] args) throws Exception {
        this.args = args;
        posible = positive = 0;
    }

    private double checkDocumentation() throws Exception {
        parseArgs();
        double ans = 0, tmp = 0;
        // D.dbg(lang, author);
        // D.dbg("args", args);

        int idxFiles = idxArg(args, "-files") + 1;
        int files = 0;
        for (; idxFiles < args.length; idxFiles++) {
            tmp = checkFile(args[idxFiles]);
//			 D.dbg("tmp", tmp);
            ans += tmp;
            files++;
        }
//		D.dbg("ans=", ans,"files=", files);
        ans = ans / files;

        return ans;
    }

    /**
     * Revisa archivo por archivo si cumple con las etiquetas que se configuran
     * en los argumentos del script
     */
    private double checkFile(String file) throws IOException {
        double ans = 0;
        FileNavigator fn = FileNavigatorFactory.getInstance(file, lang);
        int posible = 0, positive = 0;
//		 D.dbg(fn.t);
        if (author) {
            posible++;
            if (fn.t.contains("@author")) {
                positive++;
            }
        }
        if (version) {
            posible++;
            if (fn.t.contains("@version")) {
                positive++;
            }
        }
        if (copyright) {
            posible++;
            if (fn.t.contains("@copyright")) {
                positive++;
            }
        }
        if (license) {
            posible++;
            if (fn.t.contains("@license")) {
                positive++;
            }
        }
        if (pack) {
            posible++;
            if (fn.t.contains("@package")) {
                positive++;
            }
        }
        // TODO:
        // param "-param=";
        // ans "-return=");	

//		D.dbg("posible=", posible,"positive=", positive);
        ans = (double) positive / (double) posible;
        return ans;
    }

    private void parseArgs() throws Exception {
        lang = arg(args, "-language=");
        if (lang == null) {
            throw new Exception("-language tiene que ser especificado");
        }
        author = (arg(args, "-author") != null);// todo tiene que venir decir
        // true o false
        version = (arg(args, "-version") != null);
        copyright = (arg(args, "-copyright") != null);
        license = (arg(args, "-license") != null);
        pack = (arg(args, "-package") != null);

        param = arg(args, "-param=");
        ans = arg(args, "-return=");
//		 D.dbg("parseArgs", author, version, copyright, license, pack, ans);
    }

    /**
     * Retorna el valor de un parametro si se tiene por ejemplo language=cpp
     * retorna cpp
     *
     * @param String [] args los argumentos de este script
     *
     * @param String param el argumento a buscar
     *
     * @return String el contenido del parametro
     */
    private static String arg(String[] args, String param) {
        for (int i = 0; i < args.length; i++) {
            if (args[i].startsWith(param)) {
                return args[i].substring(param.length());
            }
        }
        return null;
    }

    /**
     * Localiza un parametro
     *
     * @param args los argumentos de este script
     *
     * @param param el argumento a buscar
     *
     * @return el indice del parametro
     */
    private static int idxArg(String[] args, String param) {
        for (int i = 0; i < args.length; i++) {
            if (args[i].startsWith(param)) {
                return i;
            }
        }
        return -1;
    }
}
