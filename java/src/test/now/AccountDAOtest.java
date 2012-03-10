package now.test;

import com.google.gson.JsonObject;
import junit.framework.JUnit4TestAdapter;
import now.AccountDAO;
import org.junit.After;
import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;

import java.sql.Connection;
import java.sql.Statement;

public class AccountDAOtest {
    private AccountDAO accountDAO;

    public static junit.framework.Test suite() {
        return new JUnit4TestAdapter(AccountDAOtest.class);
    }

    @Before
    public void setUp() {
        accountDAO = new AccountDAO();
        try {
            Connection conn = accountDAO.getConnection();
            Statement deleteTestAcccount = conn.createStatement();
            deleteTestAcccount.execute("DELETE FROM ACCOUNT");
            conn.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @After
    public void tearDown() {
    }

    @Test
    public void testCreate() {
        JsonObject account = new JsonObject();
        account.addProperty("userName", "test_account_1");
        account.addProperty("firstName", "Jacob");
        account.addProperty("lastName", "Andresen");
        account.addProperty("password", "jacob");
        try {
            int accountId = accountDAO.create(account);
            JsonObject account2 = accountDAO.retrieve(accountId);

            Assert.assertEquals("userName should be equal",
                                account.get("userName").toString(),
                                account2.get("userName").toString());
        } catch (Exception e) {
            System.err.println("something went wrong:" + e.getMessage());
            e.printStackTrace();
        }
    }

    @Test
    public void testUpdate() {
        try {
            JsonObject account = new JsonObject();
            account.addProperty("userName", "test_account_2");
            account.addProperty("firstName", "Jacob");
            account.addProperty("lastName", "Andresen2");
            account.addProperty("password", "jacob");
            int accountId = accountDAO.create(account);

            JsonObject account2 = accountDAO.retrieve(accountId);
            account2.remove("firstName");
            account2.addProperty("firstName", "Jacob2");
            accountDAO.update(account2);

            JsonObject account3 = accountDAO.retrieve(accountId);
            Assert.assertEquals(account2.get("firstName"), account3.get("firstName"));
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Test
    public void testDestroy() {
        try {
            JsonObject account = new JsonObject();
            account.addProperty("userName", "test_account_3");
            account.addProperty("firstName", "Jacob");
            account.addProperty("lastName", "Andresen2");
            account.addProperty("password", "jacob");
            int accountId = accountDAO.create(account);
            accountDAO.destroy(accountId);
            JsonObject accountNotFound = accountDAO.retrieve(accountId);
            Assert.assertNull(accountNotFound);
        } catch (Exception e) {
            e.printStackTrace();
        }

    }
}
