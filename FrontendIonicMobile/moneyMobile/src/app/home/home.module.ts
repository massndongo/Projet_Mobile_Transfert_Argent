import { FraisCalculatorComponent } from './../frais-calculator/frais-calculator.component';
import { CodeModalComponent } from './../code-modal/code-modal.component';
import { DepotModalComponent } from './../depot-modal/depot-modal.component';
import { DepotFormulaireComponent } from './../depot-formulaire/depot-formulaire.component';
import { AccueilComponent } from './../accueil/accueil.component';
import { ConnexionComponent } from './../connexion/connexion.component';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { HomePageRoutingModule } from './home-routing.module';


import { HomePage } from './home.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    IonicModule,
    HomePageRoutingModule
  ],
  declarations: [
    HomePage,
    AccueilComponent,
    ConnexionComponent,
    DepotFormulaireComponent,
    DepotModalComponent,
    CodeModalComponent,
    FraisCalculatorComponent,
  ]
})
export class HomePageModule {}
