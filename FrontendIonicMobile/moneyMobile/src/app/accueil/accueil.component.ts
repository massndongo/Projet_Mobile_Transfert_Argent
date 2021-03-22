import { AuthService } from './../services/auth.service';
import { UserService } from './../services/user.service';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Storage } from '@ionic/storage';
import jwt_decode from "jwt-decode";
import { Subject } from 'rxjs';
import { Location } from '@angular/common';

@Component({
  selector: 'app-accueil',
  templateUrl: './accueil.component.html',
  styleUrls: ['./accueil.component.scss'],
})
export class AccueilComponent implements OnInit {

  comptes: any;

  private  _refreshNeeded$ = new Subject<void>();

  constructor(private userService: UserService, private router: Router, private location : Location, private storage : Storage) { }

  ngOnInit() {
    this.userService.getCompteByUsername(this.userUsername()).subscribe(
      (compteElements : any) => {
        this.comptes = compteElements.agence.compte.solde
        console.log(this.comptes)
      },
      err=>{
        console.log("erreur");

      }
    );
    this.reloadComponent();
  }

  refresh(): void {
    this.router.navigateByUrl("/refresh", { skipLocationChange: true }).then(() => {
      console.log(decodeURI(this.location.path()));
      this.router.navigate([decodeURI(this.location.path())]);
    });
  }

  refreshNeeded$() {
    return this._refreshNeeded$ ;
  }

  reloadComponent() {
    let currentUrl = this.router.url;
    this.router.routeReuseStrategy.shouldReuseRoute = () => false;
    this.router.onSameUrlNavigation = 'reload';
    this.refresh();
    return this.router.navigate(['/acceuil']);
  }

  getCompte(){
    return this.userService.getCompteByUsername(this.userUsername()).subscribe(
      (compteElements : any) => {
        this.comptes = compteElements.agence.compte.solde,
        console.log(this.comptes)
      }
    ), this.reloadComponent();
  }

  tokenElement() {
    let token = localStorage.getItem('token');
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    var element = JSON.parse(jsonPayload);

    return element;
  }

  userRole () {
    let role = this.tokenElement();
    return role["roles"];
  }

  userUsername() {
    let username = this.tokenElement();
    return username["username"];
  }

  Admin() {
    if (this.userRole() == "ROLE_ADMIN_AGENCE" || this.userRole() == "ROLE_ADMIN_SYSTEME") {
      return true;
    }
  }

}
