import { AuthService } from './../services/auth.service';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage';

@Component({
  selector: 'app-connexion',
  templateUrl: './connexion.component.html',
  styleUrls: ['./connexion.component.scss'],
})
export class ConnexionComponent implements OnInit {

  loginForm: FormGroup;
  hide = true;

  usernameFormControl = new FormControl('', [Validators.required]);
  passwordFormControl = new FormControl('', [Validators.required]);

  constructor(
    private formBuilder: FormBuilder,
    private storage: Storage,
    private authService: AuthService,
    private router: Router,
    private navCtrl: NavController) { }

  ngOnInit() {
    this.loginForm  =  this.formBuilder.group({
      username: this.usernameFormControl,
      password: this.passwordFormControl
    });
  }

  logIn(){
    console.log(this.loginForm);

    this.authService.loginUser(this.loginForm.value)
      .subscribe(
        res => {
          const decodeToken = this.authService.decodeToken(res.token)
          this.authService.saveToken('token', decodeToken),
          localStorage.setItem('token', res.token),
          this.router.navigateByUrl('/accueil');
        },
        err => {
          console.log(err)
          console.log("erreur");

        }
      )
  }

}
