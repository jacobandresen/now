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
            deleteTestAcccount.execute("DELETE FROM ACCOUNT WHERE USER_NAME='test_account_1'");
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

        //TODO: Make sure that the record does not exists prior to this
        JsonObject account = new JsonObject();
        account.addProperty("userName", "test_account_1");
        account.addProperty("firstName", "Jacob");
        account.addProperty("lastName", "Andresen");
        account.addProperty("password", "jacob");
        try {
            int accountId = accountDAO.create(account);
            JsonObject account2 = accountDAO.retrieve(accountId);

            Assert.assertEquals("userName should be equal", account.get("userName").toString(), account2.get("userName").toString());
        } catch (Exception e) {
            System.err.println("something went wrong:" + e.getMessage());
            e.printStackTrace();
        }
    }
}
