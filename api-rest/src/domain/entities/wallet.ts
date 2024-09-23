export class Wallet {
  public balance: number;

  constructor({ balance }: { balance: number }) {
    this.balance = balance;
  }
}