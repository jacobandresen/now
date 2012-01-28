package now;

public class DAO {

  public void create(Object object) {
  }

  public Object retrieve(int id) {
  }

  public void update(Object object) {
  }

  public void destroy(int id) {
  }

  public Object findById (int id) {
  } 

  public Object find (String SQLFragment) {
  }

  private Object retrieveObjectBySQL(String SQL) {
  }

  private String generateCreateSQL(Object object) {
  }

  private String generateUpdateSQL(Object object) {
  }

  private String generateDestroySQL(int id) {
  }

  private String getTablename() {
  }

  private List<String> getSQLAttributeNames() {
  }

  private List<String> getAttributeNames() {
  }

  private String camelize(String SQLName) {
  }
}
