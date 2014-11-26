import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;

/**
 * @version 0.1
 * @author Oscar Chamat
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*!
 * Clase que realiza la lectura de un archivo con estructura de comentarios
 * perteneciente a el lenguaje drRacket o drScheme
 */
public class SchemeFileNavigator extends FileNavigator {
	BufferedReader input;
	String line = "";
	int lineNumber;

	/**
	 * Constructor de un lector de archivo drScheme
	 * 
	 * @param String file la ruta hasta el archivo a leer
	 */
	SchemeFileNavigator(String file) throws IOException {
		super();
		input = new BufferedReader(new FileReader(file));
		lineNumber = 0;
		t = new Tree();
		while (true) {
			if (getLine().equals("")) {
				readLine();
			}
			if (getLine() == null) {
				break;
			}
			int state = checkState();

			switch (state) {
			case STRING:
				startString();
				break;
			case BLOCK_C:
				t.nodes.add(startBlockComment());
				break;
			case LINE_C:
				t.nodes.add(startLinesComment());
				break;
			case NOTHING:
				consumLine();
				break;
			default:
				break;
			}
		}
	}

	static final int STRING = 1, BLOCK_C = 2, LINE_C = 3, NOTHING = -1;

	/**
	 * Clasifica que empieza en esta linea
	 * 
	 * @return int STRING , BLOCK_C comentario en bloque /*, LINE_C comentario
	 * en linea, NOTHING linea normal;
	 */
	private int checkState() {
		char[] l = getLine().toCharArray();
		char c;
		for (int i = 0; i < l.length - 1; i++) {
			c = l[i];
			if (c == ';') {
				return LINE_C;
			}
			if (c == '#') {
				c = l[i + 1];
				if (c == '|') {
					return BLOCK_C;
				}
			} else if (c == '"') {
				return STRING;
			}
		}
		return NOTHING;
	}

	/**
	 * elimina el primer string que haya en la linea
	 */
	private Node startString() {
		int idx = getLine().indexOf('"');
		consumLine(idx + 1);
		idx = getLine().indexOf('"');
		consumLine(idx + 1);
		return null;
	}

	/**
	 * Obtiene los comentarios que vienen por lineas
	 * 
	 * @return Node n con el comment que tiene la linea en que inicia y en la
	 * que finaliza
	 */
	private Node startLinesComment() throws IOException {
		Node n = new Node(lineNumber);
		String comment = "";
		consumLine(getLine().indexOf(";"));
		do {
			comment += consumLine() + "\n";
			readLine();
		} while (getLine().trim().startsWith(";"));
		n.comment = comment;
		n.endComment = lineNumber;
		return n;
	}

	/**
	 * Obtiene los comentarios que vienen en bloques
	 * 
	 * @return Node n con el comment que tiene la linea en que inicia y en la
	 * que finaliza
	 */
	private Node startBlockComment() throws IOException {
		Node n = new Node(lineNumber);
		String comment = "";
		consumLine(getLine().indexOf("#|"));
		while (!getLine().contains("|#")) {
			comment += consumLine() + "\n";
			readLine();
		}
		;
		comment += consumLine(getLine().indexOf("|#"));
		n.comment = comment;
		n.endComment = lineNumber;
		return n;
	}

	/**
	 * consume el buffer de linea actual hasta idx inclusivo
	 * 
	 * @param int idx tal que retorna lineActual.subtring(0,idx)
	 * 
	 * @return line la linea en el buffer hasta idx lineActual.subtring(0,idx)
	 */
	String consumLine(int idx) {// idx es inclusivo
		String ans = line.substring(0, idx);
		line = line.substring(idx);
		return ans;
	}

	/**
	 * consume el buffer de linea actual
	 * 
	 * @return line la linea en el buffer
	 */
	private String getLine() {
		return line;
	}

	/**
	 * Consume el buffer de linea actual
	 * 
	 * @return line toda la linea en el buffer
	 */
	private String consumLine() {
		String ans = line;
		line = "";
		return ans;
	}

	/**
	 * lee una linea y la pone en el buffer
	 */
	private void readLine() throws IOException {
		lineNumber++;
		// input.read();
		line = input.readLine();
	}
}
