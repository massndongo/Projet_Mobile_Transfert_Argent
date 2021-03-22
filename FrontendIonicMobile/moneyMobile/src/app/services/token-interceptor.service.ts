import { Observable } from 'rxjs';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpEvent } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage';

@Injectable({
  providedIn: 'root'
})
export class TokenInterceptorService implements HttpInterceptor {
  myToken: string;
  constructor(
    private storage: Storage
  ) { }
  intercept(req:HttpRequest<unknown>, next:HttpHandler) : Observable<HttpEvent<unknown>> {
    const token = localStorage.getItem('token');

    if (token) {
       req = req.clone(
        {
          headers: req.headers.set('Authorization', 'bearer ' + token)
        }
      )
    }
    return next.handle(req);
  }
}
