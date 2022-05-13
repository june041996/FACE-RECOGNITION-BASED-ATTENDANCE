package com.example.attendance.webservice;

public class ApiUtils {
  //public static final String BASE_URL = "http://192.168.1.11/test/";
public static final String BASE_URL = "http://192.168.1.8:8000/api/";

    private ApiUtils() {
    }

    public static APIService getAPIService() {

        return RetrofitClient.getClient(BASE_URL).create(APIService.class);
    }
}