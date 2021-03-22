import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  userUrl = "http://127.0.0.1:8000/api/user";

  constructor(private http: HttpClient) { }

  getCompteByUsername(username){
    return this.http.get(`${this.userUrl}/${username}/compte`);
  }
}
