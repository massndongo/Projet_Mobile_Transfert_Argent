import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class TransactionService {
  private url = "http://127.0.0.1:8000";
  private fraisUrl = "http://127.0.0.1:8000/api/user/frais";
  private transactionsUrl = "http://127.0.0.1:8000/api/user/transactions/depot";

  constructor(
    private http: HttpClient
  ) { }

  getFraisMontant(montant: number){
    return this.http.get(`${this.fraisUrl}/${montant}`);
  }
  addDepot(data: any){
    return this.http.post<any>(this.transactionsUrl, data);
  }
  getTotal (frais:number, montant: number){
    return this.http.get(`http://127.0.0.1:8000/api/user/totalToGive/${frais}/${montant}`);
  }
  getTransactionByCode(code: string){
    return this.http.get(`${this.url}/api/user/transaction/${code}`)
  }
  retrait(data){
    return this.http.put(`${this.url}/api/user/transactions/retrait`, data);
  }
}
