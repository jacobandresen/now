package now;

import com.google.gson.JsonElement;
import com.google.gson.JsonObject;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public abstract class DAO {

    public void create(JsonObject json) {
        String SQL = this.generateCreateSQL(json);
    }

    public JsonObject retrieve(int id) {
        return this.findById(id);
    }

    public void update(JsonObject json) {
        String SQL = this.generateUpdateSQL(json);

    }

    public void destroy(int id) {
    }

    public JsonObject findById(int id) {
        JsonObject json = new JsonObject();
        return json;
    }

    public Object find(String SQLFragment) {
        JsonObject json = new JsonObject();
        return json;
    }

    private JsonObject retrieveObjectBySQL(String SQL) {
        JsonObject json = new JsonObject();
        return json;
    }

    private String generateCreateSQL(JsonObject json) {
        return "";
    }

    private String generateUpdateSQL(JsonObject json) {
        String tableName = this.getTableName();
        String id = json.get("id").toString();
        String SQL = "UPDATE " + tableName + "where " + tableName + "_id='" + id + "' set ";
        for (Map.Entry<String, JsonElement> entry : json.entrySet()) {
            entry.getKey();
        }
        return SQL;
    }

    private String generateDestroySQL(int id) {
        return "";
    }

    private List<String> getSQLAttributeNames()
            throws SQLException, ClassNotFoundException {

        ArrayList<String> SQLAttributeNames = new ArrayList<String>();
        String tableName = getTableName();
        String SQL = "SELECT attname FROM pg_attribute, pg_type where typename=? and attrelid = typrelid AND attname NOT IN ('cmin','cmax', 'ctid', 'oid', 'tableoid', 'xmin', 'xmax')";
        Connection connection = getConnection();
        PreparedStatement attributeSelector = connection.prepareStatement(SQL);
        attributeSelector.setString(1, tableName);
        ResultSet resultSet = attributeSelector.executeQuery();
        while( resultSet.next()){
            String attributeName = resultSet.getString(1);
            SQLAttributeNames.add(attributeName);
        }
        connection.close();
        return SQLAttributeNames;
    }

    private List<String> getAttributeNames() {
        return new ArrayList<String>();
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