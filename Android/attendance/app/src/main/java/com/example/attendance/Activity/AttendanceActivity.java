package com.example.attendance.Activity;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.provider.MediaStore;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.example.attendance.R;
import com.example.attendance.webservice.ApiUtils;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.util.List;
import java.util.Locale;

import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.Callback;

public class AttendanceActivity extends AppCompatActivity {

    private final static int REQUEST_IMAGE_CAPTURE = 124;
    public static final int RESULT_OK = -1;
    Button btn_attendance, btn_location;
    TextView tv;
    FusedLocationProviderClient fusedLocationProviderClient;
    String id_subject,mssv;

    public static final String MSSV_KEY = "mssv_key";
    private SharedPreferences sharedpreferences;
    public static final String SHARED_PREFS = "shared_prefs";
    ProgressDialog progressDoalog;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_attendance);

        progressDoalog = new ProgressDialog(AttendanceActivity.this);
        progressDoalog.setMessage("Đang tải...");
        //progressDoalog.setTitle("ProgressDialog bar example");
        progressDoalog.setProgressStyle(ProgressDialog.STYLE_SPINNER);

        Bundle b= getIntent().getBundleExtra("data1");
        id_subject= b.getString("id_subject");

        sharedpreferences = this.getSharedPreferences(SHARED_PREFS, Context.MODE_PRIVATE);
        mssv = sharedpreferences.getString(MSSV_KEY, null);

        Toast.makeText(this, id_subject+" "+mssv, Toast.LENGTH_SHORT).show();
        tv = findViewById(R.id.tv);
        btn_attendance = findViewById(R.id.btn_attendance);
        btn_location = findViewById(R.id.btn_location);
        fusedLocationProviderClient = LocationServices.getFusedLocationProviderClient(this);
        btn_location.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (ActivityCompat.checkSelfPermission(getApplicationContext(),
                        Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED) {
                    getLocation();
                } else {
                    ActivityCompat.requestPermissions(AttendanceActivity.this,
                            new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, 44);
                }
            }
        });



        btn_attendance.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // makin a new intent for opening camera
                Intent intent = new Intent(
                        MediaStore.ACTION_IMAGE_CAPTURE);
                if (intent.resolveActivity(
                        getPackageManager())
                        != null) {
                    startActivityForResult(
                            intent, REQUEST_IMAGE_CAPTURE);
                } else {
                    // if the image is not captured, set
                    // a toast to display an error image.
                    Toast
                            .makeText(
                                    AttendanceActivity.this,
                                    "Something went wrong",
                                    Toast.LENGTH_SHORT)
                            .show();
                }
            }
        });
    }

    @SuppressLint("MissingPermission")
    private void getLocation() {


        fusedLocationProviderClient.getLastLocation().addOnCompleteListener(new OnCompleteListener<Location>() {
            @Override
            public void onComplete(@NonNull Task<Location> task) {
                Location location = task.getResult();
                if (location != null) {
                    Geocoder geocoder = new Geocoder(AttendanceActivity.this, Locale.getDefault());
                    try {
                        List<Address> addresses = geocoder.getFromLocation(
                                location.getLatitude(), location.getLongitude(), 1
                        );
                        tv.setText(addresses.get(0).getLatitude() + " "+addresses.get(0).getLongitude() + " "+
                                addresses.get(0).getCountryName());
                    } catch (IOException e) {
                        e.printStackTrace();
                    }

                }
                else{
                    tv.setText("ERR");
                }
            }
        });

    }

    @Override
    public void onActivityResult(int requestCode,
                                 int resultCode,
                                 @Nullable Intent data)
    {
        // after the image is captured, ML Kit provides an
        // easy way to detect faces from variety of image
        // types like Bitmap

        super.onActivityResult(requestCode, resultCode,
                data);
        if (requestCode == REQUEST_IMAGE_CAPTURE
                && resultCode == RESULT_OK) {
            Bundle extra = data.getExtras();
            Bitmap bitmap = (Bitmap)extra.get("data");
            //detectFace(bitmap);
            //imageView.setImageBitmap(bitmap);

            ByteArrayOutputStream baos = new ByteArrayOutputStream();
            bitmap.compress(Bitmap.CompressFormat.JPEG, 50, baos);
            byte[] imageBytes = baos.toByteArray();
            String imageCode = Base64.encodeToString(imageBytes, Base64.DEFAULT);
            progressDoalog.show();
            ApiUtils.getAPIService().attendance(imageCode, id_subject, mssv).enqueue(new Callback<ResponseBody>() {

                @Override
                public void onResponse(Call<ResponseBody> call, retrofit2.Response<ResponseBody> response) {
                    try {
                        String status = response.body().string().toString().trim();
                        Log.e("response", status);
                        if (status.equals("1")){
                            Toast.makeText(AttendanceActivity.this, "Điểm danh thành công", Toast.LENGTH_SHORT).show();
                            tv.setText("Điểm danh thành công");
                        }else{
                            if(status.equals("6")){
                                Toast.makeText(AttendanceActivity.this, "Đã điểm danh", Toast.LENGTH_SHORT).show();
                                tv.setText("Đã điểm danh");

                            }else{
                                if(status.equals("5")){
                                    Toast.makeText(AttendanceActivity.this, "Không đúng thời gian điểm danh", Toast.LENGTH_SHORT).show();
                                    tv.setText("Không đúng thời gian điểm danh");

                                }else{
                                    if(status.equals("4")){
                                        Toast.makeText(AttendanceActivity.this, "Không đúng ngày học", Toast.LENGTH_SHORT).show();
                                        tv.setText("Không đúng ngày học");

                                    }else{
                                        Toast.makeText(AttendanceActivity.this, "Không thành công", Toast.LENGTH_SHORT).show();
                                        tv.setText("Không thành công");
                                    }
                                }
                            }

                        }
                        Toast.makeText(AttendanceActivity.this, status , Toast.LENGTH_SHORT).show();

                    } catch (Exception e) {
                        Toast.makeText(AttendanceActivity.this,"ERR",Toast.LENGTH_SHORT).show();
                        Log.e("onResponse", "Error");
                        e.printStackTrace();
                    }
                    if (progressDoalog.isShowing()) {
                        progressDoalog.dismiss();
                    }
                }
                @Override
                public void onFailure(Call<ResponseBody> call, Throwable t) {
                    if (progressDoalog.isShowing()) {
                        progressDoalog.dismiss();
                    }
                    Toast.makeText(AttendanceActivity.this,"Kiểm tra lại ",Toast.LENGTH_SHORT).show();
                    Log.e("onFailure", t.toString());
                }
            });
        }
    }
}