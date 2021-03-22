import { environment } from './../../environments/environment';
import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ClientService {

  private url = "http://127.0.0.1:8000/api/user/client";

  constructor(private http : HttpClient) { }

  getClientByCni(nci:string){
    return this.http.get(`${this.url}/${nci}`);
  }
}
