import java.util.Arrays;
/**
* @author Oscar Chamat -civilian (chamatoscar@gmail.com)
* @version 0.3
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

/*!
 * Pequeña clase que ayuda a debugear haciendo las lineas de debug 
 * más cortas y mas visibles
 */
public class D {
    /**
     * Imprime el param o System.out.println(Arrays.deepToString(o));
     * @param Objet ... o
     * */
	static public void dbg(Object... o) {
		System.out.println(Arrays.deepToString(o));
	}
}
