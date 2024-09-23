import { describe, expect, test, jest } from '@jest/globals';
import { CONFIGURATION } from "../src/config/configuration";
import { Server } from "../src/presentation/server";
import { beforeEach } from 'node:test';

jest.mock("../src/presentation/server");

describe("App testing", () => {

  test("Should call server start", async () => {
    
    await import("../src/app");

    expect(Server).toHaveBeenCalledTimes(1);
    expect(Server).toHaveBeenCalledWith(CONFIGURATION.PORT);
    expect(Server.prototype.start).toHaveBeenCalled();
  })
})