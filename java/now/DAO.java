package now;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public abstract class DAO {

    public void create(Object object) {
    }

    public Object retrieve(int id) {
        //ClassName  without DAO
        String DAOClassName = this.getClass().getName();
        String className = DAOClassName.replace("DAO", "");
        Object object = new Object();
        return object;
    }

    public void update(Object object) {
    }

    public void destroy(int id) {
        Object object = new Object();
        return object;
    }

    public Object findById(int id) {
    }

    public Object find(String SQLFragment) {
        Object object = new Object();
        return object;
    }

    private Object retrieveObjectBySQL(String SQL) {
        Object object = new Object();
        return object;
    }

    private String generateCreateSQL(Object object) {
        return "";
    }

    private String generateUpdateSQL(Object object) {
        return "";
    }

    private String generateDestroySQL(int id) {
        return "";
    }


    private String[] getSQLAttributeNames() {
        return new String[3];
    }

    private String[] getAttributeNames() {
        return new String[3];
    }

    private String camelize(String SQLName) {
        return "";
    }

    private String getTableName() {
        return "";
    }

    private Connection getConnection()
            throws ClassNotFoundException, SQLException {
        String url = "host=localhost user=postgres password=postgres";
        Class.forName("org.postgresql.Driver");
        Connection conn = DriverManager.getConnection(url, "postgres", "postgres");
        return conn;
    }
}