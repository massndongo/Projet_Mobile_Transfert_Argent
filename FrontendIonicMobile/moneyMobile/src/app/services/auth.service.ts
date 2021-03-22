import { NavController } from '@ionic/angular';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage';
import jwt_decode from "jwt-decode";

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private loginCheckUrl = "http://127.0.0.1:8000/api/login_check";
  constructor(
    private http : HttpClient,
    private storage: Storage,
    private navCtrl:NavController    ) { }
  loginUser(user: any){
    return this.http.post<any>(this.loginCheckUrl, user);
  }

  loggedIn(){
    return this.storage.get('token');
  }
  public decodeToken(token: any): string{
    return jwt_decode(token);
  }
  async getToken() {
    let token = await this.storage.get('token');
    return token;
  }

  public saveToken(keyToken: string, value: string): void{
    this.storage.set(keyToken, value);
  }

  goToHome(){
    this.navCtrl.navigateForward('/acceuil');
  }
}
