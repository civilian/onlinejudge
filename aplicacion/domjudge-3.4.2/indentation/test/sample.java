// This sample doesn't compile but help's but showing the differente code 
// snippets that help you keep the indentation style
import java.io.File;
import java.io.IOException;
import java.util.Arrays;
import java.util.HashMap;
import javax.swing.JFrame;

public class sample extends JFrame implements Runnable {

    String[] a = new String[] {
        "Sunday",               // Sunday
            "Monday",           // Monday
            "Tuesday",          // Tuesday
            "Wednesday",        // Wednesday
            "Thursday",         // Thursday
            "Friday",           // Friday
            "Saturday"          // Saturday
    };

    private sample() {
    }

    void foo() {
        while (bar > 0) {
            System.out.println();
            bar--;
        }

        if (oatmeal == tasty) {
            System.out.println("Oatmeal is good and good for you");
        } else if (oatmeal == yak) {
            System.out.println("Oatmeal tastes like sawdust");
        } else {
            System.out.println("tell me pleeze what iz dis 'oatmeal'");
        }

        switch (suckFactor) {
        case 1:
            System.out.println("This sucks");
            break;
        case 2:
            System.out.println("This really sucks");
            break;
        case 3:
            System.out.println("This seriously sucks");
            break;
        default:
            System.out.println("whatever");
            break;
        }

        if (superHero == theTick) {
            System.out.println("Spoon!");
        }

        foo(i, j);

        a = b + c;
        z = (2 * x) + (3 * y);
        count++;
        i--;

        for (int i = 0; i < 10; i++) {
            getPancakes(syrupQuantity, butterQuantity);
        }

        (MyClass) v.get(3);

        if (hungry) {
            while (pancakes < 7) {
                for (int i = 0; i < 10; i++) {
                    try {
                    } catch(TooManyPancakesException e) {
                    }
                }

            }
        }

        foo(x);                 // YES!
        x++;

        y += 100 * x;           // YES!
        x++;

        /*
         * "Any fool can write code that a computer can understand. Good
         * programmers write code that humans can understand." --- Martin
         * Fowler, Refactoring: Improving the Design of Existing Code 
         */
        int secondWide = 12;
        int firstWide = doFoo(20, secondWide);
        doBar(firstWide, secondWide);
        int totalWide = firstWide + secondWide;

    }

    void tooLongMethod() {
        boolean isTickSidekick = ((sidekick == arthur)
                                  || (sidekick == speak));
        if ((hero == theTick) && isTickSidekick) {
        }
    }

    public static void happyBirthday(int age) { // WRONG!
        // If you're in the US, some birthdays are special:
        // 16 (sweet sixteen)
        // 21 (age of majority)
        // 25, 50, 75 (quarter centuries)
        // 30, 40, 50, ... etc (decades)
        if ((age == 16) || (age == 21)
            || ((age > 21) && (((age % 10) == 0) || ((age % 25) == 0)))) {
            System.out.println("Super special party, this year!");
        } else {
            System.out.println("One year older. Again.");
        }
    }

    public static void happyBirthday(int age) { // Right!
        boolean sweet_sixteen = (age == 16);
        boolean majority = (age == 21);
        boolean adult = (age > 21);
        boolean decade = (age % 10) == 0;
        boolean quarter = (age % 25) == 0;

        if (sweet_sixteen || majority || (adult && (decade || quarter))) {
            System.out.println("Super special party, this year!");
        } else {
            System.out.println("One year older. Again.");
        }
    }

    // This actualy work amazing for debuging
    static void dbg(Object ... o) {
        System.out.println(Arrays.deepToString(o));
    }

    static void callingDebug() {
        dbg(3);
        String[]b = new String[] {
            "Sunday",           // Sunday
                "Monday",       // Monday
                "Tuesday",      // Tuesday
                "Wednesday",    // Wednesday
                "Thursday",     // Thursday
                "Friday",       // Friday
                "Saturday"      // Saturday
        };
        dbg(b);
        HashMap < String, Person > ha = new HashMap < String, Person > ();
        Person him = new Person().sFirstName = "civilian";
        ha.put("Oscar", him);
        dbg(ha);
        // dbg(Whatever_i_want_as_long_as_the_objet_has_an_toString_method);

    }

    static class Person {

        private String sFirstName;      // NO! (Hungarian notation: s for
        // String)
        private String firstName;       // YES!
        private String mLastName;       // NO! (Scope identification: m
        // for member variable)
        private String _lastName;       // NO! (Scope identification: _
        // for member variable)
        private String lastName;        // YES!

        // ...
        @Override public String toString() {
            return "Person{" + "sFirstName=" + sFirstName +
                ", firstName=" + firstName + ", mLastName=" +
                mLastName + ", _lastName=" + _lastName +
                ", lastName=" + lastName + '}';
        }
    }
    private static final File getDestinationFile(File dest,
                                                 String packageName,
                                                 String filename, int prio)
    throws IOException, FooException {
        switch (prio) {
        case Priority.ERROR_INT:
        case Priority.FATAL_INT:
            color = Color.red;
            break;

            case Priority.WARN_INT:color = Color.blue;
            break;

            default:color = Color.black;
            break;
        }
        return null;
    }

    public void severalParameters(String one, int two, String three,
                                  StringObject four, AnotherObject five) {

        if ((condition1 && condition2)
            || (condition3 && condition4) || !(condition5 && condition6)) {
            doSomethingAboutIt();
        }

        vector.add(new AppServerReference("RemoteApplicationManager",
                                          poa.create_reference_with_id
                                          ("RemoteApplicationManager".getBytes
                                           (),
                                           RemoteApplicationManagerHelper.id
                                           ())));

        doublette[InteressentenPflegeController.GEBURTSDATUM] =
            versichertenResultSetRow[i].field[0].substring(0,
                                                           2) + "."
            + versichertenResultSetRow[i].field[0].substring(2,
                                                             4) + "."
            + versichertenResultSetRow[i].field[0].substring(4, 6);
    }

    public static void main(String[]argv) {
        callingDebug();
    }

    @Override public void run() {
        throw new UnsupportedOperationException("Not supported yet.");
    }
}
