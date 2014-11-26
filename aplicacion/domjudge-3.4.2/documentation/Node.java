import java.util.Arrays;

/**
 * @version 0.1
 * @author Oscar Chamat
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*!
 * Clase que representa un nodo sintactico resultante de un analisis
 */
public class Node {
	String comment;// no incluye el primer /*
	int endComment, iniComment;
	int endMethod, iniMethod;

	/**
	 * Constructor de un nodo sintactico para la version 0.1 solo tiene en
	 * cuenta los comentarios, donde inician y donde finalizan
	 * @param String comment el comentario de una o varias lineas
	 * @param int endComment en que linea termina el comentario.
	 * @param int iniComment en que linea inicia el comentario. 
	 */
	public Node(String comment, int endComment, int iniComment) {
		super();
		this.comment = comment;
		this.endComment = endComment;
		this.iniComment = iniComment;
	}

	/**
	 * Constructor de un nodo sintactico para la version 0.1 solo tiene en
	 * cuenta los comentarios, donde inician y donde finalizan
	 * @param String comment el comentario de una o varias lineas
	 * @param int iniComment en que linea inicia el comentario. 
	 */
	public Node(int iniComment) {
		super();
		this.iniComment = iniComment;
	}

	/**
	 * Representación en string de el nodo actual
	 * @return String la representación.
	 */
	@Override
	public String toString() {
		return "Node [comment=" + comment + ", endComment=" + endComment
				+ ", iniComment=" + iniComment + ", endMethod=" + endMethod
				+ ", iniMethod=" + iniMethod + "]";
	}

}
