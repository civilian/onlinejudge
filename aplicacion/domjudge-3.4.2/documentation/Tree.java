import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;

/**
 * @version 0.1
 * @author Oscar Chamat
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*!
 * Clase que representa el arbol sintactico resultante de un analisis
 */
public class Tree {
	ArrayList<Node> nodes;

	/**
	 * Constructor de la clase incializa los nodos vacios del arbol
	 */
	public Tree() {
		this.nodes = new ArrayList<Node>();
	}

	/**
	 * Representación en string de el nodo actual
	 * 
	 * @return String la representación.
	 */
	@Override
	public String toString() {
		String ans = "";
		for (Node n : nodes) {
			ans += "\n" + n.toString();
		}
		return ans;
	}

	/**
	 * Chequea si una etiqueta de documentacion es utilizada en los nodos
	 * 
	 * @param String string la etiqueta a ser chequeada
	 * 
	 * @return true la etiqueta es utilizada; false no esta utilizada
	 * propiamente.
	 */
	public boolean contains(String string) {
		for (Node n : nodes) {
			int idx = n.comment.indexOf(string);
			if (idx != -1) {
//				 D.dbg("encontre ", string, " en ", n.toString(), "idx", idx);
				int idxCon = Math.min(n.comment.indexOf("@", idx + 1),
									n.comment.indexOf("\n", idx + 1));
				idxCon = idxCon == -1 ? idxCon = n.comment.length() : idxCon;
				String contenido = n.comment.substring(idx + string.length(),
						idxCon);
//				 D.dbg(idxCon, contenido);
				if (!contenido.trim().equals("")) {
					return true;
				}
			}
		}
		return false;
	}

}
