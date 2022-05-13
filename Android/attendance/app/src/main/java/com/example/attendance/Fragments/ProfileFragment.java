package com.example.attendance.Fragments;


import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;


import com.example.attendance.Activity.ChangePassActivity;
import com.example.attendance.Activity.LoginActivity;
import com.example.attendance.R;

import java.util.ArrayList;
import java.util.List;



public class ProfileFragment extends Fragment  {

    public static final String SHARED_PREFS = "shared_prefs";
    public static final String ID_KEY = "id_key";
    public static final String MSSV_KEY = "mssv_key";
    private SharedPreferences sharedpreferences;
    TextView btnLogout,tvUsername,btnChangePass;
    String id,user;
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_profile,null);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        sharedpreferences = getActivity().getSharedPreferences(SHARED_PREFS, Context.MODE_PRIVATE);
        tvUsername = view.findViewById(R.id.tvUsername);
        id = sharedpreferences.getString(ID_KEY, null);
        tvUsername.setText(id);
        user = sharedpreferences.getString(MSSV_KEY, null);

        btnLogout = view.findViewById(R.id.idBtnLogout);

        btnLogout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                SharedPreferences.Editor editor = sharedpreferences.edit();
                editor.clear();
                editor.apply();
                Intent i = new Intent(getContext(), LoginActivity.class);
                startActivity(i);

            }
        });
        btnChangePass = view.findViewById(R.id.btDoiPass);
        btnChangePass.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Bundle b = new Bundle();
                b.putString("user",user);
                Intent intent  = new Intent(getContext(), ChangePassActivity.class);
                intent.putExtra("change",b);
                startActivity(intent);
            }
        });
    }



}


