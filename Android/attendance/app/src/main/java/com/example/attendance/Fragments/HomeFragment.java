package com.example.attendance.Fragments;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.os.Bundle;
import android.util.Base64;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.core.app.ActivityCompat;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.LinearSnapHelper;
import androidx.recyclerview.widget.RecyclerView;
import androidx.recyclerview.widget.SnapHelper;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;


import com.example.attendance.Adapter.allSubjectAdapter;
import com.example.attendance.Adapter.subjectAdapter;
import com.example.attendance.MainActivity;
import com.example.attendance.R;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;
import java.util.Timer;
import java.util.TimerTask;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.DialogFragment;

import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

import com.example.attendance.object.Subject;
import com.example.attendance.webservice.ApiUtils;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.FirebaseApp;
import com.google.firebase.ml.vision.FirebaseVision;
import com.google.firebase.ml.vision.common.FirebaseVisionImage;
import com.google.firebase.ml.vision.common.FirebaseVisionPoint;
import com.google.firebase.ml.vision.face.FirebaseVisionFace;
import com.google.firebase.ml.vision.face.FirebaseVisionFaceDetector;
import com.google.firebase.ml.vision.face.FirebaseVisionFaceDetectorOptions;
import com.google.firebase.ml.vision.face.FirebaseVisionFaceLandmark;

import java.util.List;

import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class HomeFragment extends Fragment {

    private RecyclerView rv;
    private List<Subject> subjectList;
    private allSubjectAdapter adapter;
    SwipeRefreshLayout swipeRefreshLayout;
    String mssv;
    public static final String MSSV_KEY = "mssv_key";
    private SharedPreferences sharedpreferences;
    public static final String SHARED_PREFS = "shared_prefs";
    ProgressDialog progressDoalog;
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_home, null);

    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        init(view);
        loadData();
        setClick();

    }

    private void setClick() {
        swipeRefreshLayout.setColorSchemeResources(R.color.design_default_color_primary,R.color.design_default_color_primary_dark);
        swipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                Toast.makeText(getContext(), "Refresh", Toast.LENGTH_SHORT).show();
                loadData();
                swipeRefreshLayout.setRefreshing(false);
            }
        });
    }

    private void init(View view) {

        swipeRefreshLayout=view.findViewById(R.id.swipeRefresh);
        rv = (RecyclerView) view.findViewById(R.id.rv);
        rv.setHasFixedSize(true);
        rv.setLayoutManager(new LinearLayoutManager(view.getContext()));
        subjectList = new ArrayList<>();
        sharedpreferences = getActivity().getSharedPreferences(SHARED_PREFS, Context.MODE_PRIVATE);
        mssv = sharedpreferences.getString(MSSV_KEY, null);
        progressDoalog = new ProgressDialog(getContext());
        progressDoalog.setMessage("Đang tải...");
        //progressDoalog.setTitle("ProgressDialog bar example");
        progressDoalog.setProgressStyle(ProgressDialog.STYLE_SPINNER);

    }

    private void loadData() {
        progressDoalog.show();
        ApiUtils.getAPIService().getAllSubject(mssv).enqueue(new Callback<List<Subject>>() {
            @Override
            public void onResponse(Call<List<Subject>> call, Response<List<Subject>> response) {
                try {
                    subjectList.clear();
                    for (int i = 0; i < response.body().size(); i++) {
                        Subject subject = new Subject(response.body().get(i).getName(),
                                response.body().get(i).getId(),
                                response.body().get(i).getTime_start(),
                                response.body().get(i).getTime_end(),
                                response.body().get(i).getDay_start(),
                                response.body().get(i).getDay_end(),
                                response.body().get(i).getDoW(),
                                response.body().get(i).getId_subject(),
                                response.body().get(i).getMssv(),
                                response.body().get(i).getName_teacher(),
                                response.body().get(i).getCount()

                        );
                        subjectList.add(subject);
                    }
                    setupData(subjectList);
                } catch (Exception e) {
                    Log.d("onResponse", "Error");
                    e.printStackTrace();
                }
                if (progressDoalog.isShowing()) {
                    progressDoalog.dismiss();
                }
            }
            @Override
            public void onFailure(Call<List<Subject>> call, Throwable t) {
                if (progressDoalog.isShowing()) {
                    progressDoalog.dismiss();
                }
                Toast.makeText(getContext(),t.toString(),Toast.LENGTH_SHORT).show();
                Log.d("onFailure", t.toString());
            }
        });
    }
    private void setupData(List<Subject> subjects) {
        adapter = new allSubjectAdapter(subjects, getContext());
        rv.setAdapter(adapter);
    }



}