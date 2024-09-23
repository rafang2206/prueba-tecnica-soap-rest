export class WalletRechargeDto {
  public document: string;
  public phone: number;
  public amount: number;

  constructor({ document, phone, amount } : { document: string, phone: number, amount: number }) {
    this.document = document;
    this.phone = phone;
    this.amount = amount;
  }
}