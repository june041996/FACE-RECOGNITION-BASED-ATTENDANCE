package com.example.attendance.Adapter;

        import android.content.Context;
        import android.content.Intent;
        import android.content.SharedPreferences;
        import android.graphics.Bitmap;
        import android.os.Bundle;
        import android.provider.MediaStore;
        import android.util.Base64;
        import android.util.Log;
        import android.view.LayoutInflater;
        import android.view.View;
        import android.view.ViewGroup;
        import android.widget.Button;
        import android.widget.CheckBox;
        import android.widget.CompoundButton;
        import android.widget.ImageView;
        import android.widget.TextView;
        import android.widget.Toast;

        import androidx.annotation.NonNull;
        import androidx.annotation.Nullable;
        import androidx.recyclerview.widget.RecyclerView;

        import com.example.attendance.Activity.AttendanceActivity;
        import com.example.attendance.Activity.LoginActivity;
        import com.example.attendance.MainActivity;
        import com.example.attendance.R;
        import com.example.attendance.interfaces.IRecyclerItemClickListener;
        import com.example.attendance.object.Subject;
        import com.example.attendance.webservice.APIService;
        import com.example.attendance.webservice.ApiUtils;

        import java.io.ByteArrayOutputStream;
        import java.util.List;

        import okhttp3.ResponseBody;
        import retrofit2.Call;
        import retrofit2.Callback;
        import retrofit2.Response;

public class checkAttendanceAdapter extends RecyclerView.Adapter<checkAttendanceAdapter.ViewHolder> {
    List<Subject> subjectList ;
    private Context context;
    APIService mAPIService;
    private final static int REQUEST_IMAGE_CAPTURE = 124;
    public static final int RESULT_OK = -1;
    String name,id;
    public static final String SHARED_PREFS = "shared_prefs";
    public static final String ID_KEY = "id_key";
    private SharedPreferences sharedpreferences;

    public checkAttendanceAdapter(List<Subject> subjectList, Context context) {
        this.subjectList = subjectList;
        this.context = context;

    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view= LayoutInflater.from(parent.getContext()).inflate(R.layout.item_check_attendance,parent,false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Subject subject=subjectList.get(position);
        holder.tvNameSubject.setText(subject.getName());
        holder.tvNameTeacher.setText(subject.getName_teacher());
        sharedpreferences = context.getSharedPreferences(SHARED_PREFS, Context.MODE_PRIVATE);
        id = sharedpreferences.getString(ID_KEY, null);

        ApiUtils.getAPIService().getCount(id,subject.getId()).enqueue(new Callback<ResponseBody>() {
            @Override
            public void onResponse(Call<ResponseBody> call, retrofit2.Response<ResponseBody> response) {
                try {
                    String status = response.body().string().toString().trim();
                    Log.e("response", status);
                    holder.tvCount.setText(status);
                } catch (Exception e) {
                    Toast.makeText(context,"Error",Toast.LENGTH_SHORT).show();
                    Log.e("onResponse", "Error");
                    e.printStackTrace();
                }
            }
            @Override
            public void onFailure(Call<ResponseBody> call, Throwable t) {
                Toast.makeText(context,R.string.failed,Toast.LENGTH_SHORT).show();
                Log.e("onFailure", t.toString());
            }
        });
      /*  holder.btnAttendance.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // makin a new intent for opening camera
                Intent intent = new Intent(
                        MediaStore.ACTION_IMAGE_CAPTURE);
                if (intent.resolveActivity(
                        context.getPackageManager())
                        != null) {
                    startActivityForResult(
                            intent, REQUEST_IMAGE_CAPTURE);
                } else {
                    // if the image is not captured, set
                    // a toast to display an error image.
                    Toast
                            .makeText(
                                    context,
                                    "Something went wrong",
                                    Toast.LENGTH_SHORT)
                            .show();
                }
            }
        } );*/


        holder.setRecyclerItemClickListener(new IRecyclerItemClickListener() {
            @Override
            public void onClick(View view, int position) {
                Toast.makeText(view.getContext(), "ok", Toast.LENGTH_SHORT).show();
            }
           /* @Override
            public void onClick(View view, int position) {
                ApiUtils.getAPIService().countView(theoDoi.getId()).enqueue(new Callback<String>() {
                    @Override
                    public void onResponse(Call<String> call, Response<String> response) {

                    }

                    @Override
                    public void onFailure(Call<String> call, Throwable t) {

                    }
                });
                Bundle b =new Bundle();
                b.putSerializable("truyen",theoDoi);

                Intent intent = new Intent(context, AttendanceActivity.class    );
                //intent.putExtra("data",b);
                context.startActivity(intent);
            }*/
        });
    }


    /*public void onActivityResult(int requestCode,
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


            ByteArrayOutputStream baos = new ByteArrayOutputStream();
            bitmap.compress(Bitmap.CompressFormat.JPEG, 50, baos);
            byte[] imageBytes = baos.toByteArray();
            String imageCode = Base64.encodeToString(imageBytes, Base64.DEFAULT);

            ApiUtils.getAPIService().attendance(imageCode).enqueue(new Callback<ResponseBody>() {
                @Override
                public void onResponse(Call<ResponseBody> call, retrofit2.Response<ResponseBody> response) {
                    try {
                        String status = response.body().string().toString().trim();
                        Log.e("response", status);

                        Toast.makeText(context, status, Toast.LENGTH_SHORT).show();

                    } catch (Exception e) {
                        Toast.makeText(context,"Error",Toast.LENGTH_SHORT).show();
                        Log.e("onResponse", "Error");
                        e.printStackTrace();
                    }
                }
                @Override
                public void onFailure(Call<ResponseBody> call, Throwable t) {
                    Toast.makeText(context,"Failed",Toast.LENGTH_SHORT).show();
                    Log.e("onFailure", t.toString());
                }
            });
        }
    }*/

    @Override
    public int getItemCount() {
        return subjectList.size();
    }

    public class ViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener{
        public TextView tvNameSubject,tvNameTeacher,tvCount;

        IRecyclerItemClickListener recyclerItemClickListener;
        Button btnAttendance;

        public void setRecyclerItemClickListener(IRecyclerItemClickListener recyclerItemClickListener) {
            this.recyclerItemClickListener = recyclerItemClickListener;
        }

        public ViewHolder(View convertView) {
            super(convertView);

            tvNameSubject = convertView.findViewById(R.id.tvNameSubject);
            tvNameTeacher = convertView.findViewById(R.id.tvNameTeacher);
            tvCount = convertView.findViewById(R.id.tvCount);

            convertView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            recyclerItemClickListener.onClick(view, getAdapterPosition());
        }
    }

}
