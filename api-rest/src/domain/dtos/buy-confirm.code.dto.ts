export class BuyConfirmCodeDto {
  public code: string;
  public sessionId: string;

  constructor({ code, sessionId } : { code: string, sessionId: string }) {
    this.code = code;
    this.sessionId = sessionId;
  }
}