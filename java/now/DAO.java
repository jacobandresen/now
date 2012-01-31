package now;

import com.google.gson.JsonElement;
import com.google.gson.JsonObject;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public abstract class DAO {
    public int create(JsonObject json)
            throws SQLException, ClassNotFoundException {
        String tableName = getTableName();
        String identifier = tableName + "_id";
        Connection conn = getConnection();

        StringBuilder tableBuilder = new StringBuilder();
        StringBuilder valueBuilder = new StringBuilder();
        tableBuilder.append("INSERT INTO ");
        tableBuilder.append(tableName);
        tableBuilder.append("(");
        valueBuilder.append("(");
        List<String> columnNames = getColumnNames();

        String delimiter = "";
        for (String columnName : columnNames) {
            if (!columnName.equals(identifier)) {
                tableBuilder.append(delimiter);
                valueBuilder.append(delimiter);
                tableBuilder.append(columnName);
                valueBuilder.append('?');
                delimiter = ",";
            }
        }
        tableBuilder.append(")");
        valueBuilder.append(")");
        tableBuilder.append(" VALUES ");
        tableBuilder.append(valueBuilder.toString());
        String SQL = tableBuilder.toString();

        PreparedStatement createStatement = conn.prepareStatement(SQL, Statement.RETURN_GENERATED_KEYS);
        int pos = 1;
        for (String columnName : columnNames) {
            if (!columnName.equals(identifier)) {
                JsonElement jsonElement = json.get(camelize(columnName));
                createStatement.setString(pos++, jsonElement.getAsString());
            }
        }
        createStatement.executeUpdate();

        ResultSet rs = createStatement.getGeneratedKeys();
        int key = -1;
        if (rs.next()) {
            key = rs.getInt(1);
        }
        return key;
    }

    public JsonObject retrieve(int id)
            throws SQLException, ClassNotFoundException {
        String tableName = getTableName();
        String idName = tableName + "_id";

        String SQL = "SELECT * from " + tableName + " where " + idName + "=" + id;
        Connection conn = getConnection();
        Statement retrieveStatement = conn.createStatement();
        ResultSet resultSet = retrieveStatement.executeQuery(SQL);
        JsonObject json = new JsonObject();
        if (resultSet.next()) {
            for (String columnName : getColumnNames()) {
                String jsonName = camelize(columnName);
                json.addProperty(jsonName, resultSet.getString(columnName));
            }
        }
        conn.close();
        return json;
    }

    public void update(JsonObject json)
            throws SQLException, ClassNotFoundException {
        String tableName = getTableName();
        String identifier = tableName + "_id";

        List<String> columnNames = getColumnNames();

        String updateFragment = "UPDATE " + tableName + " where " + identifier + " = " + json.get("id").toString() + "SET ";
        StringBuilder queryBuilder = new StringBuilder();
        queryBuilder.append(updateFragment);

        String delimiter = "";
        for (String columnName : columnNames) {
            queryBuilder.append(delimiter);
            if (!columnName.equals(identifier)) {
                queryBuilder.append(columnName);
                queryBuilder.append("=");
                queryBuilder.append(camelize(columnName));
            }
            delimiter = ",";
        }

        String SQL = queryBuilder.toString();
        Connection conn = getConnection();
        PreparedStatement updateStatement = conn.prepareCall(SQL);
        int pos = 1;
        for (String columnName : columnNames) {
            if (!columnName.equals(identifier)) {
                JsonElement jsonElement = json.get(camelize(columnName));
                updateStatement.setString(pos++, jsonElement.getAsString());
            }
        }
        updateStatement.executeUpdate();
    }

    public void destroy(int id) {
    }

    private List<String> getColumnNames()
            throws SQLException, ClassNotFoundException {
        List<String> columnNames = new ArrayList<String>();

        Connection conn = getConnection();
        String tableName = getTableName();
        DatabaseMetaData md = conn.getMetaData();
        ResultSet columnsResultSet = md.getColumns(null, null, tableName, "%");

        while (columnsResultSet.next()) {
            String columnName = columnsResultSet.getString(4);
            columnNames.add(columnName);
        }
        return columnNames;
    }

    private String camelize(String SQLName) {
        String toks[] = SQLName.split("_");

        String tableName = getTableName();
        String identifier = tableName + "_id";
        if (SQLName.equals(identifier)) {
            return "id";
        }

        StringBuilder sb = new StringBuilder();
        sb.append(toks[0]);
        for (int i = 1; i < toks.length; i++) {
            sb.append(toks[i].substring(0, 1).toUpperCase());
            sb.append(toks[i].substring(1));

        }
        return sb.toString();
    }

    private String getTableName() {
        String className = this.getClass().getSimpleName();
        return className.replace("DAO", "").toLowerCase();

    }

    public Connection getConnection()
            throws ClassNotFoundException, SQLException {
        String url = "jdbc:postgresql";
        Class.forName("org.postgresql.Driver");
        Connection conn = DriverManager.getConnection(url, "postgres", "postgres");
        return conn;
    }
}