package com.example.attendance.Activity;

import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;


import com.example.attendance.MainActivity;
import com.example.attendance.R;
import com.example.attendance.webservice.ApiUtils;

import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.Callback;

public class LoginActivity extends AppCompatActivity {

    private EditText edtMSSV;
    private EditText edtPassWord;
    private Button btnLogin;

    public static final String SHARED_PREFS = "shared_prefs";
    public static final String MSSV_KEY = "mssv_key";
    public static final String PASSWORD_KEY = "password_key";
    public static final String ID_KEY = "id_key";

    SharedPreferences sharedpreferences;
    String mssv, password,id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        sharedpreferences = getSharedPreferences(SHARED_PREFS, Context.MODE_PRIVATE);
        id = sharedpreferences.getString(ID_KEY,null);
        mssv = sharedpreferences.getString(MSSV_KEY, null);
        password = sharedpreferences.getString(PASSWORD_KEY, null);
        addControl();
        addEvent();
    }

    private void addEvent() {
        btnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String mssv = edtMSSV.getText().toString().trim();
                String password = edtPassWord.getText().toString().trim();
                if(checkEditText(edtMSSV) && checkEditText(edtPassWord)){
                    ApiUtils.getAPIService().login(mssv,password).enqueue(new Callback<ResponseBody>() {
                        @Override
                        public void onResponse(Call<ResponseBody> call, retrofit2.Response<ResponseBody> response) {
                            try {
                                String status = response.body().string().toString().trim();
                                Log.e("response", status);
                                if(status.equals("0")){
                                    Toast.makeText(LoginActivity.this, R.string.username_incorred, Toast.LENGTH_SHORT).show();
                                }else{
                                    if(status.equals("2")){
                                        Toast.makeText(LoginActivity.this, R.string.password_incorred, Toast.LENGTH_SHORT).show();
                                    }else{
                                        SharedPreferences.Editor editor = sharedpreferences.edit();
                                        editor.putString(ID_KEY,status);
                                        editor.putString(MSSV_KEY, edtMSSV.getText().toString());
                                        editor.putString(PASSWORD_KEY, edtPassWord.getText().toString());
                                        editor.apply();
                                        Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                                        startActivity(intent);
                                    }
                                }
                            } catch (Exception e) {
                                Toast.makeText(LoginActivity.this,"Error",Toast.LENGTH_SHORT).show();
                                Log.e("onResponse", "Error");
                                e.printStackTrace();
                            }
                        }
                        @Override
                        public void onFailure(Call<ResponseBody> call, Throwable t) {
                            Toast.makeText(LoginActivity.this,R.string.failed,Toast.LENGTH_SHORT).show();
                            Log.e("onFailure", t.toString());
                        }
                    });
                }
            }
        });

    }

    @Override
    protected void onStart() {
        super.onStart();
        if(mssv!=null && password!=null){
            Intent intent = new Intent(LoginActivity.this,MainActivity.class);
            startActivity(intent);
        }
    }

    private void addControl() {
        edtMSSV = (EditText) findViewById(R.id.editUsername);
        edtPassWord = (EditText) findViewById(R.id.editPassword);
        btnLogin = (Button) findViewById(R.id.btnLogin);

    }

    private boolean checkEditText(EditText editText) {
        if (editText.getText().toString().trim().length() > 0)
            return true;
        else {
            editText.setError("Vui lòng nhập dữ liệu!");
        }
        return false;
    }
}