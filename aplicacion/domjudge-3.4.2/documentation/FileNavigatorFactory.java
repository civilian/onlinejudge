
import java.io.IOException;
import java.nio.channels.UnsupportedAddressTypeException;

/**
 * @version 0.1
 * @author Oscar Chamat
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*!
 * Clase factoria parte de un patro de diseño factory para poder navegar
 * facilmente diferentes tipos de archivo, crea un tipo de navegador dependiendo
 * del lenguaje
 */
public class FileNavigatorFactory {

    /**
     * Retorna una instancia de FileNavigator
     * 
     * @param
     */
    public static FileNavigator getInstance(String file, String lang)
            throws IOException {
        if (lang.equals("cpp")) {
            return new CPPFileNavigator(file);
        } else if (lang.equals("java")) {
            return new CPPFileNavigator(file);
        } else if (lang.equals("scheme")) {
            return new SchemeFileNavigator(file);
        }
        throw new UnsupportedOperationException(String.format(
                "El lenguaje %s no se soporta todavía", lang));
    }
}
